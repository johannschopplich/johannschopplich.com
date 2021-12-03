/// <reference lib="WebWorker" />

const sw = self as unknown as ServiceWorkerGlobalScope & typeof globalThis;

declare const VERSION: string;
declare const KIRBY_PANEL_SLUG: string;
declare const KIRBY_API_SLUG: string;

const LANG = navigator.language.startsWith("de") ? "de" : "en";
const MAX_CACHED_PAGES = 0;
const MAX_CACHED_IMAGES = 50;
const FETCH_TIMEOUT = 7500;

const CACHE_KEYS = {
  STATIC: `static-${VERSION}`,
  PAGES: `pages-${VERSION}`,
  IMAGES: "images",
};

const ALLOWED_HOSTS = [sw.location.host];

const EXCLUDED_PATH_PREFIXES = [
  `/${KIRBY_API_SLUG}/`,
  `/${KIRBY_PANEL_SLUG}/`,
  `/media/${KIRBY_PANEL_SLUG}/`,
  "/media/plugins/",
];

const PRECACHE_URLS = [
  // @ts-expect-error: precache manifest not on `self`
  ...(sw.__PRECACHE_MANIFEST || []),
  `/${LANG}`,
  `/${LANG}/blog`,
  `/${LANG}/offline`,
];

/**
 * Stash an item in specified cache
 */
async function stashInCache(
  cacheName: string,
  request: Request,
  response: Response
) {
  const cache = await caches.open(cacheName);
  cache.put(request, response);
}

/**
 * Limit the number of items in a specified cache
 */
async function trimCache(cacheName: string, maxItems: number): Promise<void> {
  const cache = await caches.open(cacheName);
  const keys = await cache.keys();
  if (keys.length > maxItems) {
    await cache.delete(keys[0]);
    return trimCache(cacheName, maxItems);
  }
}

sw.addEventListener("message", ({ data }) => {
  if (data?.command === "trimCaches") {
    if (MAX_CACHED_PAGES) trimCache(CACHE_KEYS.PAGES, MAX_CACHED_PAGES);
    if (MAX_CACHED_IMAGES) trimCache(CACHE_KEYS.IMAGES, MAX_CACHED_IMAGES);
  }
});

sw.addEventListener("install", (event) => {
  sw.skipWaiting();

  // These items must be cached for the Service Worker to complete installation
  event.waitUntil(
    (async () => {
      const cache = await caches.open(CACHE_KEYS.STATIC);
      await cache.addAll(
        PRECACHE_URLS.map((url) => new Request(url, { credentials: "include" }))
      );
    })()
  );
});

sw.addEventListener("activate", (event) => {
  sw.clients.claim();

  // Remove caches whose name is no longer valid
  event.waitUntil(
    (async () => {
      const keys = await caches.keys();
      for (const key of keys) {
        if (!Object.values(CACHE_KEYS).includes(key)) {
          await caches.delete(key);
        }
      }
    })()
  );
});

sw.addEventListener("fetch", (event) => {
  const { request } = event;
  const url = new URL(request.url);
  const destination = request.headers.get("Accept");

  if (request.method !== "GET") return;
  if (!ALLOWED_HOSTS.find((host) => url.host === host)) return;
  if (EXCLUDED_PATH_PREFIXES.some((path) => url.pathname.startsWith(path)))
    return;

  const isHTML = destination?.startsWith("text/html");
  const isImage = destination?.startsWith("image");
  const isAsset = /^\/(assets|dist)\//.test(url.pathname);

  // Cache-first strategy for images, network-first strategy
  // for everything else
  event.respondWith(
    (async function () {
      // Lookup cached response of the given request
      const cachedResponse = await caches.match(request);

      // Return cached image, if available
      if (cachedResponse && (isImage || isAsset)) return cachedResponse;

      // Create a controller to abort fetch requests after timeout
      const controller = new AbortController();
      const { signal } = controller;

      const timeoutId = setTimeout(() => controller.abort(), FETCH_TIMEOUT);

      try {
        const response = await fetch(request, { signal });
        const copy = response.clone();
        clearTimeout(timeoutId);

        if (
          PRECACHE_URLS.includes(url.pathname) ||
          PRECACHE_URLS.includes(url.pathname + "/")
        ) {
          stashInCache(CACHE_KEYS.STATIC, request, copy);
        } else if (isHTML) {
          stashInCache(CACHE_KEYS.PAGES, request, copy);
        } else if (isImage) {
          stashInCache(CACHE_KEYS.IMAGES, request, copy);
        }

        return response;
      } catch (error) {
        // @ts-expect-error: error is never null
        if (error.name === "AbortError") {
          console.error("Fetch aborted after timeout for", request.url);
        }

        // Return cached response, if available
        if (cachedResponse) return cachedResponse;

        // Return precached offline page as fallback
        if (isHTML) {
          const result = await caches.match(`/${LANG}/offline`);
          if (result) return result;
        }

        // Provide a fallback for images
        if (isImage) {
          return new Response(
            `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" aria-label="Device is offline…" role="img">
  <path fill="hsl(0, 0%, 85%)" d="M0 0h400v300H0z" />
  <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="system-ui, sans-serif" font-size="72" font-weight="600">Offline</text>
</svg>`,
            {
              headers: {
                "Content-Type": "image/svg+xml",
                "Cache-Control": "no-store",
              },
            }
          );
        }

        console.error(error);
        return new Response(null, { status: 504 });
      }
    })()
  );
});

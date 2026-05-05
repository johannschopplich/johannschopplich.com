declare global {
  interface Window {
    YT: {
      ready: (callback: () => void) => void;
      Player: new (
        el: HTMLElement,
        opts: {
          width: string;
          videoId: string;
          playerVars: Record<string, string>;
          events: {
            onReady: (event: { target: { playVideo: () => void } }) => void;
          };
        },
      ) => unknown;
    };
  }
}

export class LiteYouTubeEmbed extends HTMLElement {
  videoId = "";
  playLabel = "";
  requiresYTApi = false;
  #isInitialized = false;

  static isPreconnected = false;
  static ytApiPromise: Promise<void> | undefined;

  connectedCallback() {
    if (this.#isInitialized) return;
    this.#isInitialized = true;

    this.videoId = this.getAttribute("videoid")!;

    let playBtnEl = this.querySelector<HTMLButtonElement>(".lty-playbtn")!;

    // A label for the button takes priority over a [playlabel] attribute on the custom-element
    this.playLabel =
      playBtnEl?.textContent?.trim() ||
      this.getAttribute("playlabel") ||
      "Play";

    this.dataset.title = this.getAttribute("title") || "";

    if (!this.style.backgroundImage) {
      this.style.backgroundImage = `url("https://i.ytimg.com/vi/${this.videoId}/hqdefault.jpg")`;
      this.upgradePosterImage();
    }

    if (!playBtnEl) {
      playBtnEl = document.createElement("button");
      playBtnEl.type = "button";
      playBtnEl.classList.add("lty-playbtn");
      this.append(playBtnEl);
    }

    if (!playBtnEl.textContent) {
      const playBtnLabelEl = document.createElement("span");
      playBtnLabelEl.className = "sr-only";
      playBtnLabelEl.textContent = this.playLabel;
      playBtnEl.append(playBtnLabelEl);
    }

    this.addNoscriptIframe();

    // Progressive-enhancement: an <a> child becomes the play button
    if (playBtnEl.nodeName === "A") {
      playBtnEl.removeAttribute("href");
      playBtnEl.setAttribute("tabindex", "0");
      playBtnEl.setAttribute("role", "button");
      // Anchor-as-button needs keyboard activation
      playBtnEl.addEventListener("keydown", (event) => {
        if (event.key === "Enter" || event.key === " ") {
          event.preventDefault();
          void this.activate();
        }
      });
    }

    // Warm TCP connections to YouTube origins on first interaction
    this.addEventListener("pointerover", LiteYouTubeEmbed.warmConnections, {
      once: true,
    });
    this.addEventListener("focusin", LiteYouTubeEmbed.warmConnections, {
      once: true,
    });

    this.addEventListener("click", () => {
      void this.activate();
    });

    // Safari desktop and mobile browsers don't propagate the click gesture
    // through iframe creation, so ?autoplay=1 is ignored. The YT JS API is
    // the only reliable autoplay path on those browsers.
    this.requiresYTApi =
      this.hasAttribute("js-api") ||
      navigator.vendor.includes("Apple") ||
      navigator.userAgent.includes("Mobi");
  }

  static addPrefetch(kind: string, url: string, as?: string) {
    const linkEl = document.createElement("link");
    linkEl.rel = kind;
    linkEl.href = url;
    if (as) linkEl.as = as;
    document.head.append(linkEl);
  }

  // Preconnects can't replace iframe-internal requests; the best we can do
  // is warm origins on the critical path.
  static warmConnections() {
    if (LiteYouTubeEmbed.isPreconnected) return;

    // The iframe document and most of its subresources come right off youtube.com
    LiteYouTubeEmbed.addPrefetch(
      "preconnect",
      "https://www.youtube-nocookie.com",
    );
    // The botguard script is fetched off from google.com
    LiteYouTubeEmbed.addPrefetch("preconnect", "https://www.google.com");

    LiteYouTubeEmbed.addPrefetch(
      "preconnect",
      "https://googleads.g.doubleclick.net",
    );
    LiteYouTubeEmbed.addPrefetch(
      "preconnect",
      "https://static.doubleclick.net",
    );

    LiteYouTubeEmbed.isPreconnected = true;
  }

  static fetchYTPlayerApi() {
    if (LiteYouTubeEmbed.ytApiPromise) return LiteYouTubeEmbed.ytApiPromise;
    if (window.YT?.Player) return Promise.resolve();

    LiteYouTubeEmbed.ytApiPromise = new Promise((resolve, reject) => {
      const scriptEl = document.createElement("script");
      scriptEl.src = "https://www.youtube.com/iframe_api";
      scriptEl.async = true;
      scriptEl.onload = () => window.YT.ready(resolve);
      scriptEl.onerror = reject;
      document.head.append(scriptEl);
    });
    return LiteYouTubeEmbed.ytApiPromise;
  }

  async addYTPlayerIframe() {
    await LiteYouTubeEmbed.fetchYTPlayerApi();

    const videoPlaceholderEl = document.createElement("div");
    this.append(videoPlaceholderEl);

    const playerVars = Object.fromEntries(this.getParams().entries());

    void new window.YT.Player(videoPlaceholderEl, {
      width: "100%",
      videoId: this.videoId,
      playerVars,
      events: {
        onReady: (event) => event.target.playVideo(),
      },
    });
  }

  // Add the iframe within <noscript> for indexability discoverability. See https://github.com/paulirish/lite-youtube-embed/issues/105
  addNoscriptIframe() {
    const iframeEl = this.createBasicIframe();
    const noscriptEl = document.createElement("noscript");
    // noscript children behave differently when scripting is enabled – set innerHTML instead.
    // See https://html.spec.whatwg.org/multipage/scripting.html#the-noscript-element
    noscriptEl.innerHTML = iframeEl.outerHTML;
    this.append(noscriptEl);
  }

  getParams() {
    const params = new URLSearchParams(this.getAttribute("params") || []);
    params.append("autoplay", "1");
    params.append("playsinline", "1");
    return params;
  }

  async activate() {
    if (this.classList.contains("lyt-activated")) return;
    this.classList.add("lyt-activated");

    if (this.requiresYTApi) {
      return this.addYTPlayerIframe();
    }

    const iframeEl = this.createBasicIframe();
    this.append(iframeEl);

    // Set focus for a11y
    iframeEl.focus();
  }

  createBasicIframe() {
    const iframeEl = document.createElement("iframe");
    iframeEl.width = "560";
    iframeEl.height = "315";
    // No encoding necessary as [title] is safe. https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html#:~:text=Safe%20HTML%20Attributes%20include
    iframeEl.title = this.playLabel;
    iframeEl.allow =
      "accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture";
    iframeEl.allowFullscreen = true;
    // Required by YouTube to fix Error 153
    iframeEl.referrerPolicy = "strict-origin-when-cross-origin";
    // videoId is encoded defensively as a URL component; not strictly required for XSS.
    // https://stackoverflow.com/q/64959723/89484
    iframeEl.src = `https://www.youtube-nocookie.com/embed/${encodeURIComponent(this.videoId)}?${this.getParams().toString()}`;
    return iframeEl;
  }

  // After the hqdefault loads, opportunistically swap to sddefault.webp if available.
  // See https://github.com/paulirish/lite-youtube-embed/blob/master/youtube-thumbnail-urls.md
  upgradePosterImage() {
    // Defer to reduce network contention.
    setTimeout(() => {
      const webpUrl = `https://i.ytimg.com/vi_webp/${this.videoId}/sddefault.webp`;
      const probeImg = new Image();
      probeImg.fetchPriority = "low";
      // Match amphtml's referrer policy for the thumbnail probe.
      // https://github.com/ampproject/amphtml/pull/3940
      probeImg.referrerPolicy = "origin";
      probeImg.src = webpUrl;
      probeImg.onload = (event) => {
        // YouTube serves a 120×90 placeholder instead of a 404 when the thumbnail
        // is missing, so detect by dimensions.
        const target = event.target as HTMLImageElement;
        const isPlaceholderImage =
          target.naturalHeight === 90 && target.naturalWidth === 120;
        if (isPlaceholderImage) return;

        this.style.backgroundImage = `url("${webpUrl}")`;
      };
    }, 100);
  }
}

export function setup() {
  customElements.define("lite-youtube", LiteYouTubeEmbed);
}

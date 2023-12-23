/**
 * A lightweight youtube embed. Still should feel the same to the user, just MUCH faster to initialize and paint.
 *
 * @see https://github.com/paulirish/lite-youtube-embed
 */
export class LiteYouTubeEmbed extends HTMLElement {
  videoId: string = "";
  playLabel: string = "";
  needsYouTubeApi: boolean = false;
  vendorApiPromise: Promise<void> | undefined = undefined;

  static preconnected: boolean = false;

  connectedCallback() {
    this.videoId = this.getAttribute("videoid")!;

    let playBtnEl = this.querySelector<HTMLButtonElement>(".lty-playbtn");

    // A label for the button takes priority over a `playlabel` attribute on the custom element
    this.playLabel =
      playBtnEl?.textContent?.trim() ||
      this.getAttribute("playlabel") ||
      "Play";

    if (!this.style.backgroundImage) {
      this.style.backgroundImage = `url("https://i.ytimg.com/vi/${this.videoId}/hqdefault.jpg")`;
    }

    // Set up play button, and its visually hidden label
    if (!playBtnEl) {
      playBtnEl = document.createElement("button");
      playBtnEl.type = "button";
      playBtnEl.classList.add("lty-playbtn");
      this.append(playBtnEl);
    }

    if (!playBtnEl.textContent) {
      const playBtnLabelEl = document.createElement("span");
      playBtnLabelEl.classList.add("sr-only");
      playBtnLabelEl.textContent = this.playLabel;
      playBtnEl.append(playBtnLabelEl);
    }

    playBtnEl.removeAttribute("href");

    // On hover (or tap), warm up the TCP connections we're (likely) about to use.
    this.addEventListener("pointerover", LiteYouTubeEmbed.warmConnections, {
      once: true,
    });

    // Once the user clicks, add the real iframe and drop our play button
    // TODO: In the future we could be like amp-youtube and silently swap in the iframe during idle time
    //   We'd want to only do this for in-viewport or near-viewport ones: https://github.com/ampproject/amphtml/pull/5003
    this.addEventListener("click", this.addIframe);

    // Chrome & Edge desktop have no problem with the basic YouTube Embed with ?autoplay=1
    // However Safari desktop and most/all mobile browsers do not successfully track the user gesture of clicking through the creation/loading of the iframe,
    // so they don't autoplay automatically. Instead we must load an additional 2 sequential JS files (1KB + 165KB) (un-br) for the YT Player API
    // TODO: Try loading the the YouTube API in parallel with our iframe and then attaching/playing it. #82
    this.needsYouTubeApi =
      navigator?.vendor?.includes("Apple") ||
      navigator.userAgent.includes("Mobi");
  }

  /**
   * Add a <link rel={preload | preconnect} ...> to the head
   */
  static addPrefetch(kind: string, url: string, as?: string) {
    const linkEl = document.createElement("link");
    linkEl.rel = kind;
    linkEl.href = url;
    if (as) linkEl.as = as;

    document.head.append(linkEl);
  }

  /**
   * Begin pre-connecting to warm up the iframe load
   * Since the embed's network requests load within its iframe,
   *   preload/prefetch'ing them outside the iframe will only cause double-downloads.
   * So, the best we can do is warm up a few connections to origins that are in the critical path.
   */
  static warmConnections() {
    if (LiteYouTubeEmbed.preconnected) return;

    // The iframe document and most of its subresources come right off youtube.com
    LiteYouTubeEmbed.addPrefetch(
      "preconnect",
      "https://www.youtube-nocookie.com",
    );
    // The botguard script is fetched off from google.com
    LiteYouTubeEmbed.addPrefetch("preconnect", "https://www.google.com");

    // Not certain if these ad related domains are in the critical path. Could verify with domain-specific throttling.
    LiteYouTubeEmbed.addPrefetch(
      "preconnect",
      "https://googleads.g.doubleclick.net",
    );
    LiteYouTubeEmbed.addPrefetch(
      "preconnect",
      "https://static.doubleclick.net",
    );

    LiteYouTubeEmbed.preconnected = true;
  }

  fetchVendorPlayerApi() {
    if ((window as any).YT) return;

    this.vendorApiPromise = new Promise<void>((resolve, reject) => {
      const script = document.createElement("script");
      script.src = "https://www.youtube.com/iframe_api";
      script.async = true;
      script.onload = () => {
        (window as any).YT.ready(resolve);
      };
      script.onerror = reject;
      this.append(script);
    });
  }

  async addVendorPlayerIframe(params: URLSearchParams) {
    this.fetchVendorPlayerApi();
    await this.vendorApiPromise;

    const videoPlaceholderEl = document.createElement("div");
    this.append(videoPlaceholderEl);

    // eslint-disable-next-line no-new
    new (window as any).YT.Player(videoPlaceholderEl, {
      width: "100%",
      videoId: this.videoId,
      playerVars: Object.fromEntries(params.entries()),
      events: {
        onReady: (event: any) => {
          event.target.playVideo();
        },
      },
    });
  }

  async addIframe() {
    if (this.classList.contains("lyt-activated")) return;
    this.classList.add("lyt-activated");

    const params = new URLSearchParams(this.getAttribute("params") || []);
    params.append("autoplay", "1");
    params.append("playsinline", "1");

    if (this.needsYouTubeApi) {
      return this.addVendorPlayerIframe(params);
    }

    const iframeEl = document.createElement("iframe");
    iframeEl.width = "560";
    iframeEl.height = "315";
    iframeEl.title = this.playLabel;
    iframeEl.allow =
      "accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture";
    iframeEl.allowFullscreen = true;

    // AFAIK, the encoding here isn't necessary for XSS, but we'll do it only because this is a URL
    // https://stackoverflow.com/q/64959723/89484
    iframeEl.src = `https://www.youtube-nocookie.com/embed/${encodeURIComponent(
      this.videoId,
    )}?${params.toString()}`;

    this.append(iframeEl);

    // Set focus for a11y
    iframeEl.focus();
  }
}

export function setup() {
  customElements.define("lite-youtube", LiteYouTubeEmbed);
}

interface YTPlayerOptions {
  host: string;
  width: string;
  videoId: string;
  playerVars: Record<string, string>;
  events: {
    onReady: (event: { target: { playVideo: () => void } }) => void;
  };
}

interface YTNamespace {
  ready: (callback: () => void) => void;
  Player: new (el: HTMLElement, options: YTPlayerOptions) => unknown;
}

declare global {
  interface Window {
    YT: YTNamespace;
  }
}

export class LiteYouTubeEmbed extends HTMLElement {
  videoId = "";
  playLabel = "";
  shouldUseYTApi = false;
  #isInitialized = false;

  static ytApiPromise: Promise<void> | undefined;

  connectedCallback() {
    if (this.#isInitialized) return;
    this.#isInitialized = true;

    this.videoId = this.getAttribute("videoid")!;

    let playBtnEl = this.querySelector<HTMLElement>(".lty-playbtn");

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
      const buttonEl = document.createElement("button");
      buttonEl.type = "button";
      buttonEl.classList.add("lty-playbtn");
      this.append(buttonEl);
      playBtnEl = buttonEl;
    }

    if (!playBtnEl.textContent) {
      const playBtnLabelEl = document.createElement("span");
      playBtnLabelEl.className = "sr-only";
      playBtnLabelEl.textContent = this.playLabel;
      playBtnEl.append(playBtnLabelEl);
    }

    this.addNoscriptIframe();

    // Progressive enhancement: an `<a class="lty-playbtn">`, the link to YouTube without JS, becomes the play button.
    if (playBtnEl.nodeName === "A") {
      playBtnEl.removeAttribute("href");
      playBtnEl.setAttribute("tabindex", "0");
      playBtnEl.setAttribute("role", "button");
      // Anchor-as-button needs keyboard activation.
      playBtnEl.addEventListener("keydown", (event) => {
        if (event.key === "Enter" || event.key === " ") {
          event.preventDefault();
          void this.activate();
        }
      });
    }

    this.addEventListener("click", () => {
      void this.activate();
    });

    // Safari and mobile browsers don't carry the click gesture into a new
    // iframe, so `autoplay=1` is ignored. The YT JS API is the only reliable
    // autoplay path there.
    this.shouldUseYTApi =
      this.hasAttribute("js-api") ||
      navigator.vendor.includes("Apple") ||
      navigator.userAgent.includes("Mobi");
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
      host: "https://www.youtube-nocookie.com",
      width: "100%",
      videoId: this.videoId,
      playerVars,
      events: {
        onReady: (event) => event.target.playVideo(),
      },
    });
  }

  // Adds the iframe inside a `<noscript>` so crawlers find the video. See https://github.com/paulirish/lite-youtube-embed/issues/105
  addNoscriptIframe() {
    const iframeEl = this.createBasicIframe();
    const noscriptEl = document.createElement("noscript");
    // With scripting enabled, `<noscript>` content is raw text, so the iframe goes in as markup.
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

    if (this.shouldUseYTApi) {
      try {
        return await this.addYTPlayerIframe();
      } catch (error) {
        // When the API script fails to load, say behind a content blocker,
        // the plain iframe below still plays after a second click.
        console.warn(error);
      }
    }

    const iframeEl = this.createBasicIframe();
    this.append(iframeEl);

    iframeEl.focus();
  }

  createBasicIframe() {
    const iframeEl = document.createElement("iframe");
    iframeEl.width = "560";
    iframeEl.height = "315";
    // `title` is a safe attribute and needs no encoding. https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html#:~:text=Safe%20HTML%20Attributes%20include
    iframeEl.title = this.playLabel;
    iframeEl.allow =
      "accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture";
    iframeEl.allowFullscreen = true;
    // Required by YouTube to fix Error 153.
    iframeEl.referrerPolicy = "strict-origin-when-cross-origin";
    // videoId is encoded defensively as a URL component; not strictly required for XSS.
    // https://stackoverflow.com/q/64959723/89484
    iframeEl.src = `https://www.youtube-nocookie.com/embed/${encodeURIComponent(this.videoId)}?${this.getParams().toString()}`;
    return iframeEl;
  }

  // Swaps the poster to `sddefault.webp` when YouTube has one.
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

class LiveAnnouncer extends HTMLElement {
  #assertiveRegion;
  #politeRegion;

  static {
    if (typeof window !== "undefined") {
      let tagName = "live-announcer";
      while (customElements.get(tagName)) {
        tagName += "-x";
      }
      customElements.define(tagName, LiveAnnouncer);
    }
  }

  constructor() {
    super();
    this.#assertiveRegion = this.ownerDocument.createElement("div");
    this.#assertiveRegion.setAttribute("aria-live", "assertive");
    this.#politeRegion = this.ownerDocument.createElement("div");
    this.#politeRegion.setAttribute("aria-live", "polite");

    this.attachShadow({ mode: "open" });
    this.shadowRoot?.replaceChildren(this.#assertiveRegion, this.#politeRegion);

    const stylesheet = new CSSStyleSheet();
    stylesheet.replaceSync(`\
			:host {
				clip-path: inset(50%) !important;
				height: 1px !important;
				overflow: hidden !important;
				position: absolute !important;
				white-space: nowrap !important;
				width: 1px !important;
				user-select: none !important;
			}
		`);
    this.shadowRoot?.adoptedStyleSheets.push(stylesheet);
  }

  setup({
    target = document.body,
  }: { target?: HTMLElement | ShadowRoot } = {}) {
    target?.appendChild(this);
  }

  notify(
    text: string,
    { priority = "none" }: { priority?: "none" | "important" } = {},
  ) {
    const region =
      priority === "important" ? this.#assertiveRegion : this.#politeRegion;
    region.textContent = text;
    setTimeout(() => {
      region.textContent = "";
    }, 3_000);
  }
}

let _announcer: LiveAnnouncer;

function setup() {
  if (!document.body.shadowRoot) {
    const shadow = document.body.attachShadow({ mode: "open" });
    shadow.appendChild(document.createElement("slot"));
  }
  _announcer = new LiveAnnouncer();
  // @ts-expect-error: target expects a Node
  _announcer.setup({ target: document.body.shadowRoot });
}

function notify(...args: Parameters<LiveAnnouncer["notify"]>) {
  _announcer.notify(...args);
}

export { setup, notify, LiveAnnouncer };

let sheet: CSSStyleSheet | HTMLStyleElement;
const supportsConstructableStylesheets =
  "replaceSync" in CSSStyleSheet.prototype;

export class EmojiSticker extends HTMLElement {
  #css = `
:host {
  display: inline-block;
  -webkit-user-select: none;
  user-select: none;
  filter: drop-shadow(0.5px 0.5px 2px rgba(0, 0, 0, 0.25));
}
`;

  #filterId: string;
  #emoji: string;
  #lightElement?: SVGFEPointLightElement;
  #latestMouseEvent?: MouseEvent;
  #rafId?: number;

  constructor() {
    super();
    this.#filterId = `emoji-sticker-filter-${Math.random()
      .toString(36)
      .substring(2, 15)}`;
    this.#emoji = this.textContent || "😀";
  }

  generateCss() {
    if (!sheet) {
      if (supportsConstructableStylesheets) {
        sheet = new CSSStyleSheet();
        sheet.replaceSync(this.#css);
      } else {
        sheet = document.createElement("style");
        sheet.textContent = this.#css;
      }
    }

    if (supportsConstructableStylesheets) {
      this.shadowRoot!.adoptedStyleSheets = [sheet as CSSStyleSheet];
    } else {
      this.shadowRoot!.append((sheet as HTMLStyleElement).cloneNode(true));
    }
  }

  connectedCallback() {
    this.attachShadow({ mode: "open" });
    this.generateCss();

    const filterSvg = document.createElementNS(
      "http://www.w3.org/2000/svg",
      "svg",
    );
    filterSvg.setAttribute("width", "0");
    filterSvg.setAttribute("height", "0");

    filterSvg.innerHTML = `
<filter id="${this.#filterId}" color-interpolation-filters="sRGB">
  <feGaussianBlur in="SourceAlpha" stdDeviation="3" result="blur1"></feGaussianBlur>
  <feSpecularLighting result="spec1" in="blur1" surfaceScale="5" specularConstant="0.5" specularExponent="120" lighting-color="#ffffff">
    <fePointLight x="150" y="0" z="300"></fePointLight>
  </feSpecularLighting>
  <feComposite in="spec1" in2="SourceAlpha" operator="in" result="specOut2"></feComposite>
  <feComposite in="SourceGraphic" in2="specOut2" operator="arithmetic" k1="0" k2="1" k3="1" k4="0" result="litPaint"></feComposite>
</filter>
`;

    const span = document.createElement("span");
    span.textContent = this.#emoji;
    span.style.filter = `url(#${this.#filterId})`;

    this.shadowRoot!.append(filterSvg, span);

    // Attach the light element for easy access
    this.#lightElement =
      this.shadowRoot!.querySelector<SVGFEPointLightElement>("fePointLight") ??
      undefined;

    // Listen for mouse movement to update the light position
    window.addEventListener("mousemove", this.queueMouseMove);
  }

  disconnectedCallback() {
    window.removeEventListener("mousemove", this.queueMouseMove);

    if (this.#rafId) {
      cancelAnimationFrame(this.#rafId);
      this.#rafId = undefined;
    }
  }

  queueMouseMove = (event: MouseEvent) => {
    this.#latestMouseEvent = event;
    this.#rafId ||= requestAnimationFrame(this.updateLightPosition);
  };

  updateLightPosition = () => {
    this.#rafId = undefined;

    if (!this.#latestMouseEvent || !this.#lightElement) return;

    const { top, left, width, height } = this.getBoundingClientRect();
    const centerX = left + width / 2;
    const centerY = top + height / 2;
    const dx = Math.ceil(this.#latestMouseEvent.clientX - centerX);
    const dy = Math.ceil(this.#latestMouseEvent.clientY - centerY);

    this.#lightElement.setAttribute("x", dx.toString());
    this.#lightElement.setAttribute("y", dy.toString());

    this.#latestMouseEvent = undefined;
  };
}

export function setup() {
  customElements.define("emoji-sticker", EmojiSticker);
}

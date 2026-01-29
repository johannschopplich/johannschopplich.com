// Forked from: https://github.com/hexagoncircle/click-spark
import { adoptStyles } from "./_shared";

let sharedSheet: CSSStyleSheet | HTMLStyleElement | undefined;

export class ClickSpark extends HTMLElement {
  #root = document.documentElement;
  #svg?: SVGSVGElement;

  #css = `
:host {
  display: contents;
}

svg {
  pointer-events: none;
  position: fixed;
  top: -100%;
  left: -100%;
  rotate: -20deg;
  stroke: var(--click-spark-color, currentcolor);
}

line {
  stroke-dasharray: 30;
  transform-origin: center;
}
`;

  connectedCallback() {
    this.attachShadow({ mode: "open" });
    sharedSheet = adoptStyles(this.shadowRoot!, this.#css, sharedSheet);

    const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svg.setAttribute("width", "30");
    svg.setAttribute("height", "30");
    svg.setAttribute("viewBox", "0 0 100 100");
    svg.setAttribute("fill", "none");
    svg.setAttribute("stroke-linecap", "round");
    svg.setAttribute("stroke-linejoin", "round");
    svg.setAttribute("stroke-width", "4");

    svg.innerHTML = Array.from(
      { length: 8 },
      () => '<line x1="50" y1="30" x2="50" y2="4"/>',
    ).join("");

    this.shadowRoot!.append(svg);
    this.#svg = svg;
    this.#root.addEventListener("click", this.handleClick);
  }

  disconnectedCallback() {
    this.#root.removeEventListener("click", this.handleClick);
  }

  handleClick = (event: MouseEvent) => {
    this.setSparkPosition(event);
    this.animateSpark();
  };

  animateSpark() {
    const sparks = [...this.#svg!.children];
    const size = Number.parseInt(sparks[0]?.getAttribute("y1") || "0");
    const offset = `${size / 2}px`;
    const sparkCount = sparks.length;

    for (const [i, spark] of sparks.entries()) {
      const deg = `calc(${i} * (360deg / ${sparkCount}))`;

      spark.animate(
        [
          {
            strokeDashoffset: size * 3,
            transform: `rotate(${deg}) translateY(${offset})`,
          },
          {
            strokeDashoffset: size,
            transform: `rotate(${deg}) translateY(0)`,
          },
        ],
        {
          duration: 660,
          easing: "cubic-bezier(0.25, 1, 0.5, 1)",
          fill: "forwards",
        },
      );
    }
  }

  setSparkPosition(event: MouseEvent) {
    const rect = this.#root.getBoundingClientRect();

    this.#svg!.style.left = `${
      event.clientX - rect.left - this.#svg!.clientWidth / 2
    }px`;
    this.#svg!.style.top = `${
      event.clientY - rect.top - this.#svg!.clientHeight / 2
    }px`;
  }
}

export function setup() {
  customElements.define("click-spark", ClickSpark);
}

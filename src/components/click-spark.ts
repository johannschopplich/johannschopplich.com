export class ClickSpark extends HTMLElement {
  #root = document.documentElement;
  #svg?: SVGSVGElement;

  constructor() {
    super();
  }

  connectedCallback() {
    this.attachShadow({ mode: "open" });

    this.shadowRoot!.innerHTML = `
<style>
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
</style>
<svg width="30" height="30" viewBox="0 0 100 100" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="4">
  ${Array.from(
    { length: 8 },
    (_) => `<line x1="50" y1="30" x2="50" y2="4"/>`,
  ).join("")}
</svg>
`;

    const svg = this.shadowRoot?.querySelector("svg");
    if (svg) {
      this.#svg = svg;
      this.#root.addEventListener("click", (event) => {
        this.setSparkPosition(event);
        this.animateSpark();
      });
    }
  }

  animateSpark() {
    const sparks = [...this.#svg!.children];
    const size = Number.parseInt(sparks[0].getAttribute("y1") || "0");
    const offset = `${size / 2}px`;

    const keyframes = (i: number): Keyframe[] => {
      const deg = `calc(${i} * (360deg / ${sparks.length}))`;

      return [
        {
          strokeDashoffset: size * 3,
          transform: `rotate(${deg}) translateY(${offset})`,
        },
        {
          strokeDashoffset: size,
          transform: `rotate(${deg}) translateY(0)`,
        },
      ];
    };

    for (const [i, spark] of sparks.entries()) {
      spark.animate(keyframes(i), {
        duration: 660,
        easing: "cubic-bezier(0.25, 1, 0.5, 1)",
        fill: "forwards",
      });
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

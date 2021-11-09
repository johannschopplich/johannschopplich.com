import { useBreakpoints } from "../hooks";

export default class {
  elements: HTMLElement[] = [];

  constructor(selector = "[data-zoomable]") {
    this.elements = [...document.querySelectorAll<HTMLElement>(selector)];

    const { isAbove } = useBreakpoints();
    if (isAbove("md") && this.elements.length !== 0) {
      this.init();
    }
  }

  async init() {
    const margin = parseFloat(
      getComputedStyle(document.body).getPropertyValue("font-size")
    );

    const { default: mediumZoom } = await import("medium-zoom");
    mediumZoom(this.elements, {
      background: "var(--color-background)",
      margin,
    });
  }
}

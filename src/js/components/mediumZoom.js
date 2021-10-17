import { useBreakpoints } from "../hooks";

export default class {
  constructor(selector = "[data-zoomable]") {
    const { isAbove } = useBreakpoints();
    const elements = [...document.querySelectorAll(selector)];

    if (isAbove("md") && elements.length !== 0) {
      this.init(elements);
    }
  }

  async init(elements) {
    const margin = parseFloat(
      getComputedStyle(document.body).getPropertyValue("font-size")
    );

    const { default: mediumZoom } = await import("medium-zoom");
    mediumZoom(elements, {
      background: "var(--color-background)",
      margin,
    });
  }
}

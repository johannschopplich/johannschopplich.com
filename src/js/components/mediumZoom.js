export default class {
  constructor(selector = "[data-zoomable]") {
    const isBelowMd = !matchMedia("(min-width: 768px)").matches;
    const elements = [...document.querySelectorAll(selector)];

    if (!isBelowMd && elements.length !== 0) {
      this.init(elements);
    }
  }

  async init(elements) {
    const rem = parseFloat(
      getComputedStyle(document.body).getPropertyValue("font-size")
    );

    const { default: mediumZoom } = await import("medium-zoom");
    mediumZoom(elements, {
      background: "var(--color-background)",
      margin: rem,
    });
  }
}

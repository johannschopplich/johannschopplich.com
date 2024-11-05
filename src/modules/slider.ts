export async function install() {
  const elements = document.querySelectorAll<HTMLElement>("[data-slider]");
  if (elements.length === 0) return;

  const hasTouchCapability = matchMedia("(hover: none)").matches;
  if (hasTouchCapability) return;

  const { CarouselEngine } = await import(
    "../components/slider/carousel-engine"
  );

  for (const element of elements) {
    const engine = new CarouselEngine();
    engine.init(element, { mouseDrag: true });
  }
}

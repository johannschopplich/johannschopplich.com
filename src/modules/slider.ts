export async function install() {
  const elements = document.querySelectorAll<HTMLElement>(
    "[data-scroll-snap-slider]"
  );
  if (elements.length === 0) return;

  if (matchMedia("(hover: none), (pointer: coarse)").matches) return;

  const { ScrollSnapDraggable, ScrollSnapSlider } = await import(
    "../utils/scroll-snap"
  );

  for (const element of elements) {
    new ScrollSnapSlider(element, {
      plugins: [new ScrollSnapDraggable()],
    });
  }
}

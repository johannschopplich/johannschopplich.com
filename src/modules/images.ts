import Loadeer from "loadeer";

export function install() {
  const loadeer = new Loadeer('[data-loading="lazy"]');
  loadeer.observe();

  // Auto calculate `sizes` attribute
  for (const image of document.querySelectorAll<HTMLImageElement>(
    "[data-auto-sizes]"
  )) {
    image.sizes = `${image.offsetWidth}px`;
  }
}

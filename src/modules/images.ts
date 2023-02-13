import Loadeer from "loadeer";

export function install() {
  const loadeer = new Loadeer('[data-loading="lazy"]');

  // Trigger loading images before they are in the viewport,
  // e.g. for animated slider images
  for (const image of document.querySelectorAll<HTMLImageElement>(
    "[data-trigger-load]"
  )) {
    loadeer.triggerLoad(image);
  }

  loadeer.observe();
}

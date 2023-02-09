import Loadeer from "loadeer";

export function install() {
  const loadeer = new Loadeer('[data-loading="lazy"]');

  // Trigger loading images before they are in the viewport,
  // e.g. for animated slider images
  const triggerLoad = document.querySelectorAll<HTMLImageElement>(
    "[data-trigger-load]"
  );

  for (const image of triggerLoad) {
    loadeer.triggerLoad(image);
  }

  loadeer.observe();
}

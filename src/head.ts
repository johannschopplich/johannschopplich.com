const root = document.documentElement;

if (matchMedia("(hover: none)").matches) {
  root.style.setProperty("--vh", `${window.innerHeight * 0.01}px`);
}

if (
  !matchMedia("(prefers-reduced-motion: reduce)").matches &&
  !/(gle|ing|ro)bot|crawl|spider/i.test(navigator.userAgent)
) {
  root.dataset.animatable = "true";
}

const setting = localStorage.getItem("color-schema");
if (setting) root.dataset.theme = setting;

// Reduce flickering on initial load
const docsearchRect = localStorage.getItem("algolia.docsearch.rect");
if (docsearchRect) {
  const domRect = JSON.parse(docsearchRect);
  root.style.setProperty("--docsearch-width", `${domRect.width}px`);
  root.style.setProperty("--docsearch-height", `${domRect.height}px`);
}

const root = document.documentElement;

if (
  !matchMedia("(prefers-reduced-motion: reduce)").matches &&
  !/(gle|ing|ro)bot|crawl|spider/i.test(navigator.userAgent)
) {
  root.dataset.animatable = "true";
}

const prefersDark = matchMedia("(prefers-color-scheme: dark)").matches;
const setting = localStorage.getItem("color-schema");

if (setting === "dark" || (prefersDark && setting !== "light")) {
  root.dataset.theme = "dark";
}

// Reduce flickering on initial load
const docsearchRect = localStorage.getItem("algolia.docsearch.rect");
if (docsearchRect) {
  const domRect = JSON.parse(docsearchRect);
  root.style.setProperty("--docsearch-width", `${domRect.width}px`);
  root.style.setProperty("--docsearch-height", `${domRect.height}px`);
}

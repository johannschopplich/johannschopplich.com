// @ts-check

(() => {
  const root = document.documentElement;
  const isBot = /(gle|ing|ro)bot|crawl|spider/i.test(navigator.userAgent);

  const prefersDark = matchMedia("(prefers-color-scheme: dark)").matches;
  const themeSetting = localStorage.getItem("color-schema");
  if (themeSetting === "dark" || (prefersDark && themeSetting !== "light")) {
    root.dataset.theme = "dark";
  }

  const prefersReducedMotion = matchMedia(
    "(prefers-reduced-motion: reduce)"
  ).matches;
  if (!prefersReducedMotion && !isBot) {
    root.dataset.animatable = "";
  }

  // Reduce flickering on initial load
  const docsearchRect = localStorage.getItem("algolia.docsearch.rect");
  if (docsearchRect) {
    const domRect = JSON.parse(docsearchRect);
    root.style.setProperty("--docsearch-width", `${domRect.width}px`);
    root.style.setProperty("--docsearch-height", `${domRect.height}px`);
  }
})();

(() => {
  const root = document.documentElement;
  const isBot = /(gle|ing|ro)bot|crawl|spider/i.test(navigator.userAgent);
  const themeColorMeta = document.querySelector('meta[name="theme-color"]');

  const prefersDark = matchMedia("(prefers-color-scheme: dark)").matches;
  const themeSetting = localStorage.getItem("color-schema");
  if (themeSetting === "dark" || (prefersDark && themeSetting !== "light")) {
    root.dataset.theme = "dark";
    themeColorMeta?.setAttribute("content", "#1c1917");
  }

  const prefersReducedMotion = matchMedia(
    "(prefers-reduced-motion: reduce)",
  ).matches;
  if (!prefersReducedMotion && !isBot) {
    root.dataset.animatable = "";
  }
})();

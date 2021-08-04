(() => {
  const prefersDark = matchMedia("(prefers-color-scheme: dark)").matches;
  const setting = localStorage.getItem("color-schema") || "auto";

  if (setting === "dark" || (prefersDark && setting !== "light")) {
    document.documentElement.dataset.theme = "dark";
  }

  // Fix opiniated implementation of the `vh` unit in iOS Safari
  document.documentElement.style.setProperty(
    "--vh",
    `${window.innerHeight * 0.01}px`
  );

  // Prevent FOUC by immediately adding `visibility: hidden` styles
  // on elements to animate later on
  const isCrawler = /(gle|ing|ro)bot|crawl|spider/i.test(navigator.userAgent);
  if (!isCrawler) {
    document.documentElement.dataset.animatable = "true";
  }
})();

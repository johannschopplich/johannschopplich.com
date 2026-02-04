(() => {
  const prefersDark = matchMedia("(prefers-color-scheme: dark)").matches;
  const themeSetting = localStorage.getItem("color-scheme");

  if (themeSetting === "dark" || (prefersDark && themeSetting !== "light")) {
    document.documentElement.dataset.theme = "dark";
    document
      .querySelector('meta[name="theme-color"]')
      ?.setAttribute("content", "#1c1917");
  }
})();

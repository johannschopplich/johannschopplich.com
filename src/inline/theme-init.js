(() => {
  const prefersDark = matchMedia("(prefers-color-scheme: dark)").matches;
  const themeSetting = localStorage.getItem("color-scheme");

  if (themeSetting === "dark" || (prefersDark && themeSetting !== "light")) {
    document.documentElement.dataset.theme = "dark";

    const meta = document.querySelector('meta[name="theme-color"]');

    if (meta?.dataset.dark) {
      meta.setAttribute("content", meta.dataset.dark);
    }
  }
})();

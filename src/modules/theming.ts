export const install = () => {
  const root = document.documentElement;
  const themes = ["light", "dark"];

  // Handle switching themes
  document.querySelector("#theme-switcher")?.addEventListener("click", () => {
    const currentIndex = themes.findIndex(
      (i) => (root.dataset.theme || "light") === i
    );
    const newIndex = currentIndex === 0 ? 1 : 0;

    root.dataset.theme = themes[newIndex];
    localStorage.setItem("color-schema", themes[newIndex]);
  });
};

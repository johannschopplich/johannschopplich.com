const root = document.documentElement;
const themes = ["light", "dark"];

export const install = () => {
  document.querySelector("#theme-switcher")?.addEventListener("click", () => {
    const currentTheme = root.dataset.theme;
    const newTheme = themes.find((theme) => theme !== currentTheme)!;
    root.dataset.theme = newTheme;
    localStorage.setItem("color-schema", newTheme);
  });
};

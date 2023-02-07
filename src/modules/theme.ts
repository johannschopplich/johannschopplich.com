const root = document.documentElement;
const themes = ["light", "dark"] as const;

export function install() {
  document
    .querySelector("[data-theme-switcher]")
    ?.addEventListener("click", () => {
      const currentTheme = root.dataset.theme;
      const newTheme = themes.find((theme) => theme !== currentTheme)!;
      root.dataset.theme = newTheme;
      localStorage.setItem("color-schema", newTheme);
    });
}

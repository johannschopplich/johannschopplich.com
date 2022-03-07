const root = document.documentElement;
const themes = ["light", "dark"];

export const install = () => {
  document.querySelector("#theme-switcher")?.addEventListener("click", () => {
    const currentTheme = root.dataset.theme;
    // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
    const newTheme = themes.find((theme) => theme !== currentTheme)!;
    root.dataset.theme = newTheme;
    localStorage.setItem("color-schema", newTheme);
  });
};

import { themeColor, themes } from "../constants";

export function install() {
  const themeSwitcher = document.querySelector("[data-theme-switcher]");

  themeSwitcher?.addEventListener("click", () => {
    const currentTheme = document.documentElement.dataset.theme;
    const newTheme = getNextTheme(currentTheme);

    if (!document.startViewTransition) {
      applyTheme(newTheme);
      return;
    }

    document.startViewTransition(() => applyTheme(newTheme));
  });
}

function getNextTheme(currentTheme?: string) {
  return themes.find((theme) => theme !== currentTheme)!;
}

function applyTheme(theme: (typeof themes)[number]) {
  document.documentElement.dataset.theme = theme;
  document
    .querySelector('meta[name="theme-color"]')
    ?.setAttribute("content", themeColor[theme]);
  localStorage.setItem("color-scheme", theme);
}

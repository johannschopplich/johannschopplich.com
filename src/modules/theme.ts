import { themeColor, themes } from "../constants";

const root = document.documentElement;
const themeColorMeta = document.querySelector('meta[name="theme-color"]');

export function install() {
  const themeSwitcher = document.querySelector("[data-theme-switcher]");
  themeSwitcher?.addEventListener("click", () => {
    const currentTheme = root.dataset.theme;
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
  root.dataset.theme = theme;
  themeColorMeta?.setAttribute("content", themeColor[theme]);
  localStorage.setItem("color-schema", theme);
}

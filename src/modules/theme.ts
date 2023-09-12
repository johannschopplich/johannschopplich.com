const root = document.documentElement;
const themeColorMeta = document.querySelector('meta[name="theme-color"]');
const themes = ["light", "dark"] as const;
const themeColor: Record<(typeof themes)[number], string> = {
  light: "#fefcf8",
  dark: "#18181b",
};

export function install() {
  const themeSwitcher = document.querySelector("[data-theme-switcher]");
  themeSwitcher?.addEventListener("click", switchTheme);
}

function switchTheme() {
  const currentTheme = root.dataset.theme;
  const newTheme = getNextTheme(currentTheme);
  applyTheme(newTheme);
}

function getNextTheme(currentTheme?: string) {
  return themes.find((theme) => theme !== currentTheme)!;
}

function applyTheme(theme: (typeof themes)[number]) {
  root.dataset.theme = theme;
  themeColorMeta?.setAttribute("content", themeColor[theme]);
  localStorage.setItem("color-schema", theme);
}

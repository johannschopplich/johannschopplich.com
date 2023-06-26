const root = document.documentElement;
const themes = ["light", "dark"] as const;

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

function applyTheme(theme: string) {
  root.dataset.theme = theme;
  localStorage.setItem("color-schema", theme);
}

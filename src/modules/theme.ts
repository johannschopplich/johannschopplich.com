// Named import, not a default import: Rollup turns the JSON's top-level keys into
// named exports it can tree-shake. Pulling in the default export would ship the
// entire palette to the client for the sake of two hex values.
import { colors } from "../generated/tokens.json" with { type: "json" };

const themes = ["light", "dark"] as const;

const themeColor: Record<(typeof themes)[number], string> = {
  light: colors.background.light.hex,
  dark: colors.background.dark.hex,
};

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

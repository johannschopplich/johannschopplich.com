export const THEME_COLOR_LIGHT = "#fefcf8";
export const THEME_COLOR_DARK = "#1c1917";
export const themes = ["light", "dark"] as const;
export const themeColor: Record<(typeof themes)[number], string> = {
  light: THEME_COLOR_LIGHT,
  dark: THEME_COLOR_DARK,
};

import { defineConfig, presetUno } from "unocss";
import presetIcons from "@unocss/preset-icons";

export const cliConfig = {
  outDir: "src/styles",
  watchPaths: ["site/snippets/**/*", "site/templates/**/*"],
};

export default defineConfig({
  theme: {
    colors: {
      primary: "var(--color-primary)",
      accent: "var(--color-accent)",
      contrast: {
        lowest: "var(--color-contrast-lowest)",
        lower: "var(--color-contrast-lower)",
        low: "var(--color-contrast-low)",
        medium: "var(--color-contrast-medium)",
        high: "var(--color-contrast-high)",
        higher: "var(--color-contrast-higher)",
      },
    },
    fontFamily: {
      normal: "var(--font-family-normal)",
      heading: "var(--font-family-heading)",
    },
    lineHeight: {
      normal: "var(--line-height-normal)",
      heading: "var(--line-height-heading)",
    },
  },
  rules: [
    [/^font-size-(\d+)$/, ([, d]) => ({ "font-size": `var(--text-${d})` })],
  ],
  presets: [
    presetUno(),
    presetIcons({
      scale: 1.2,
    }),
  ],
});

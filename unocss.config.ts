import { defineConfig, presetUno } from "unocss";
import presetIcons from "@unocss/preset-icons";

export function createConfig({ dev = false } = {}) {
  return defineConfig({
    envMode: dev ? "dev" : "build",
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
}

export default createConfig();

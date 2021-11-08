import { defineConfig, presetUno } from "unocss";
import presetIcons from "@unocss/preset-icons";

export function createConfig({ dev = true } = {}) {
  return defineConfig({
    envMode: dev ? "dev" : "build",
    theme: {
      colors: {
        primary: "var(--color-primary)",
        accent: "var(--color-accent)",
        "contrast-lowest": "var(--color-contrast-lowest)",
        "contrast-lower": "var(--color-contrast-lower)",
        "contrast-low": "var(--color-contrast-low)",
        "contrast-medium": "var(--color-contrast-medium)",
        "contrast-high": "var(--color-contrast-high)",
        "contrast-higher": "var(--color-contrast-higher)",
      },
      fontFamily: {
        base: "var(--font-family-base)",
        heading: "var(--font-family-heading)",
      },
      lineHeight: {
        none: "1",
        tight: "var(--line-height-heading)",
        normal: "var(--line-height-base)",
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

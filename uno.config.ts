import type { Theme } from "@unocss/preset-wind4";
import { variantMatcher } from "@unocss/preset-wind4/utils";
import {
  defineConfig,
  presetIcons,
  presetWind4,
  transformerDirectives,
} from "unocss";

export default defineConfig<Theme>({
  cli: {
    entry: {
      patterns: ["site/{snippets,templates}/**/*"],
      outFile: "src/styles/uno.css",
    },
  },
  theme: {
    colors: {
      primary: {
        DEFAULT: "var(--un-color-primary)",
        50: "var(--un-color-primary-50)",
        100: "var(--un-color-primary-100)",
        200: "var(--un-color-primary-200)",
        300: "var(--un-color-primary-300)",
        400: "var(--un-color-primary-400)",
        500: "var(--un-color-primary-500)",
        600: "var(--un-color-primary-600)",
        700: "var(--un-color-primary-700)",
        800: "var(--un-color-primary-800)",
        900: "var(--un-color-primary-900)",
        950: "var(--un-color-primary-950)",
      },
      link: {
        DEFAULT: "var(--un-color-link)",
        hover: "var(--un-color-link-hover)",
      },
      theme: {
        base: "var(--un-color-text)",
        background: "var(--un-color-background)",
      },
      contrast: {
        lowest: "var(--un-color-contrast-lowest)",
        lower: "var(--un-color-contrast-lower)",
        low: "var(--un-color-contrast-low)",
        soft: "var(--un-color-contrast-soft)",
        medium: "var(--un-color-contrast-medium)",
        high: "var(--un-color-contrast-high)",
        higher: "var(--un-color-contrast-higher)",
      },
    },
    radius: {
      DEFAULT: "0.125rem",
    },
    text: {
      xs: { fontSize: "0.75rem", lineHeight: "var(--un-line-height-normal)" },
      sm: { fontSize: "0.875rem", lineHeight: "var(--un-line-height-normal)" },
      base: { fontSize: "1rem", lineHeight: "var(--un-line-height-normal)" },
      lg: {
        fontSize: "var(--un-text-lg)",
        lineHeight: "var(--un-line-height-heading)",
      },
      xl: {
        fontSize: "var(--un-text-xl)",
        lineHeight: "var(--un-line-height-heading)",
      },
      "2xl": {
        fontSize: "var(--un-text-2xl)",
        lineHeight: "var(--un-line-height-heading)",
      },
      "3xl": {
        fontSize: "var(--un-text-3xl)",
        lineHeight: "var(--un-line-height-heading)",
      },
      "4xl": {
        fontSize: "var(--un-text-4xl)",
        lineHeight: "var(--un-line-height-heading)",
      },
    },
    font: {
      normal: "var(--un-font-family-normal)",
      heading: "var(--un-font-family-heading)",
    },
    leading: {
      normal: "var(--un-line-height-normal)",
      heading: "var(--un-line-height-heading)",
      dense: "var(--un-line-height-dense)",
    },
    container: {
      prose: "70ch",
    },
  },
  variants: [
    variantMatcher("un-light", (input) => ({
      prefix: `:root[data-theme="light"] $$ ${input.prefix}`,
    })),
    variantMatcher("un-dark", (input) => ({
      prefix: `:root[data-theme="dark"] $$ ${input.prefix}`,
    })),
  ],
  rules: [
    [
      "hyphenate",
      {
        "overflow-wrap": "break-word",

        "-webkit-hyphens": "auto",
        "-webkit-hyphenate-limit-before": "4",
        "-webkit-hyphenate-limit-after": "4",
        "-webkit-hyphenate-limit-chars": "8 4 4",
        "-webkit-hyphenate-limit-lines": "2",
        "-webkit-hyphenate-limit-last": "always",
        "-webkit-hyphenate-limit-zone": "10%",

        hyphens: "auto",
        "hyphenate-limit-chars": "8 4 4",
        "hyphenate-limit-lines": "2",
        "hyphenate-limit-last": "always",
        "hyphenate-limit-zone": "10%",
      },
    ],
  ],
  shortcuts: [
    [/^column-(\d+)$/, ([, d]) => `flex-none w-${d}/12`],
    [
      /^content-(inset|offset)-([lrtbxy])$/,
      ([, type, dir]) => {
        const property = type === "inset" ? "p" : "m";
        return `${property}${dir}-lg md:${property}${dir}-[max(4vw,1.875rem)]`;
      },
    ],
    {
      // Typography hierarchy
      title: "text-contrast-higher font-heading font-600 leading-heading",
      headline:
        "text-contrast-higher text-size-2xl leading-[1.15] font-heading font-600 md:text-size-[calc(var(--un-text-4xl)+0.5vw)] md:leading-[1.05]",
      subtext: "text-sm font-500",

      // Layout
      columns: "flex flex-wrap",
      column: "block flex-1",
      "column-narrow": "block flex-[0_0_auto] w-auto",
      "column-auto": "block flex-1 w-auto",
      "column-full": "block flex-none w-full",
      content: "px-lg md:px-[max(4vw,1.875rem)]",
      "masonry-grid":
        "grid grid-cols-[repeat(auto-fit,minmax(min(var(--masonry-column-max-width,25rem),100%),1fr))] justify-center children:self-start",

      // Components
      "underline-default":
        "underline decoration-current decoration-size-[var(--un-decoration-thickness)] underline-offset-[var(--un-decoration-offset)]",
      icon: "size-[1.25em] [&>svg]:w-full [&>svg]:h-full",
      "icon-inline":
        "inline-block select-none touch-manipulation [&>svg]:h-full [&>svg]:w-auto",
      tag: "text-size-xs leading-[1.05] font-heading font-500 whitespace-nowrap border-1 border-solid border-theme-base rounded-full px-2 py-1 un-dark:border-contrast-low",
      "button-callout":
        "inline-flex items-center text-size-xs leading-[1.05] font-heading font-500 uppercase tracking-[0.125ch] -m-2 p-2",
    },
  ],
  safelist: ["sr-only", "invisible"],
  outputToCssLayers: true,
  transformers: [transformerDirectives()],
  presets: [
    presetWind4({
      preflights: {
        reset: true,
      },
    }),
    presetIcons({
      extraProperties: {
        display: "inline-block",
        height: "1.25em",
        width: "1.25em",
        "vertical-align": "text-bottom",
      },
    }),
  ],
});

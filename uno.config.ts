import type { Theme } from "@unocss/preset-wind4";
import { parseColor, variantMatcher } from "@unocss/preset-wind4/utils";
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
        DEFAULT: "oklch(64.8% 0.055 125.2)", // #879570
        50: "oklch(97.0% 0.005 125.2)", // #F4F6F2
        100: "oklch(94.0% 0.010 125.2)", // #E9ECE5
        200: "oklch(89.0% 0.018 125.2)", // #D8DDD0
        300: "oklch(83.0% 0.030 125.2)", // #C3CBB6
        400: "oklch(74.0% 0.045 125.2)", // #A4B092
        500: "oklch(64.8% 0.055 125.2)", // #879570
        600: "oklch(55.0% 0.050 125.2)", // #6B7757
        700: "oklch(46.0% 0.045 125.2)", // #525D41
        800: "oklch(38.0% 0.040 125.2)", // #3D462F
        900: "oklch(31.0% 0.035 125.2)", // #2C3420
        950: "oklch(21.0% 0.028 125.2)", // #151B0C
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
      /^var-color-(.+)$/,
      ([, body], { theme }) => {
        const parsed = parseColor(body!, theme);
        if (!parsed) return;

        return {
          [`--un-color-${body}`]: parsed.color,
        };
      },
    ],
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
      title: "text-contrast-higher font-heading font-600 leading-heading",
      columns: "flex flex-wrap",
      column: "block flex-1",
      "column-narrow": "block flex-[0_0_auto] w-auto",
      "column-auto": "block flex-1 w-auto",
      "column-full": "block flex-none w-full",
      content: "px-lg md:px-[max(4vw,1.875rem)]",
      "underline-default":
        "underline decoration-current decoration-size-[var(--un-decoration-thickness)] underline-offset-[var(--un-decoration-offset)]",
      headline:
        "text-contrast-higher text-size-2xl leading-[1.15] font-heading font-700 md:text-size-[calc(var(--un-text-4xl)+0.5vw)] md:leading-[1.05]",
      "section-divider": "h-[min(20svh,8rem)]",
      icon: "w-[1.25em] h-[1.25em] [&>svg]:w-full [&>svg]:h-full",
      tag: "text-size-xs leading-[1.05] font-heading font-500 whitespace-nowrap border-1 border-solid border-theme-base rounded-full px-2 py-1 un-dark:border-contrast-low",
      "cta-button":
        "inline-flex items-center text-size-xs leading-[1.05] font-heading font-500 -m-2 p-2 uppercase tracking-[0.125ch]",
      "masonry-grid":
        "grid grid-cols-[repeat(auto-fit,minmax(min(var(--masonry-column-max-width,25rem),100%),1fr))] justify-center children:self-start", // grid-rows-[masonry]
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

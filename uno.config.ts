import type { Theme } from "@unocss/preset-wind4";
import {
  defineConfig,
  presetIcons,
  presetWind4,
  toEscapedSelector,
  transformerDirectives,
} from "unocss";

export default defineConfig<Theme>({
  cli: {
    entry: {
      patterns: ["site/{snippets,templates}/**/*", "src/modules/**/*"],
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
        accent: "var(--un-color-primary-accent)",
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
    [
      /^halftone-bg$/,
      ([,], { rawSelector }) => {
        const selector = toEscapedSelector(rawSelector);
        const from = "var(--un-dithered-from, var(--un-color-text))";
        const to = "var(--un-dithered-to, var(--un-color-background))";
        const textShadow = generateTextOutlineShadow(to, 2);
        const dotSize = 5;
        const dotSizeRetina = 4;

        return `
${selector} {
  text-shadow: ${textShadow};
  background-image: radial-gradient(${from} 20%, transparent 20%), radial-gradient(${from} 20%, transparent 20%);
  background-position: 0px 0px, ${dotSize / 2}px ${dotSize / 2}px;
  background-size: ${dotSize}px ${dotSize}px;
}

@media (min-resolution: 2dppx) {
  ${selector} {
    background-position: 0px 0px, ${dotSizeRetina / 2}px ${dotSizeRetina / 2}px;
    background-size: ${dotSizeRetina}px ${dotSizeRetina}px;
  }
}
`;
      },
    ],
  ],
  shortcuts: [
    [
      /^content-(inset|offset)-([lrtbxy])$/,
      ([, type, dir]) => {
        const property = type === "inset" ? "p" : "m";
        return `${property}${dir}-lg md:${property}${dir}-[max(4vw,var(--spacing-3xl))]`;
      },
    ],
    {
      // Text styles
      headline:
        "text-contrast-higher text-size-2xl leading-[1.15] font-heading font-600 md:text-size-[calc(var(--un-text-4xl)+0.5vw)] md:leading-[1.05]",
      title: "text-contrast-higher font-heading font-600 leading-heading",
      overline: "text-sm font-500 tracking-[0.125ch] uppercase",
      caption: "text-xs font-600 tracking-[0.125ch] uppercase",
      subtext: "text-sm font-500",

      // Text utilities
      "text-underline":
        "underline decoration-current decoration-size-[var(--un-decoration-thickness)] underline-offset-[var(--un-decoration-offset)]",

      // Layout
      content: "px-lg md:px-[max(4vw,var(--spacing-3xl))]",
      "section-divider": "h-$spacing-8xl md:h-[calc(var(--spacing-8xl)*1.25)]",

      // Components
      icon: "size-[1.25em] [&>svg]:size-full",
      "icon-inline":
        "inline-block select-none touch-manipulation [&>svg]:h-full [&>svg]:w-auto",
      tag: "text-size-xs leading-[1.05] font-heading font-500 whitespace-nowrap border-1 border-solid border-theme-base rounded-full px-2 py-1 dark:border-contrast-low",
      "action-link":
        "inline-flex items-center text-size-xs leading-[1.05] font-heading font-500 uppercase tracking-[0.125ch] -m-2 p-2",
    },
  ],
  safelist: ["sr-only", "invisible"],
  outputToCssLayers: true,
  transformers: [transformerDirectives()],
  presets: [
    presetWind4({
      dark: {
        dark: '[data-theme="dark"]',
        light: ':root:not([data-theme]), [data-theme="light"]',
      },
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

/**
 * Generates a text-shadow that creates an outline effect around glyphs.
 * Covers all positions in a box pattern up to the given radius.
 */
function generateTextOutlineShadow(color: string, radius = 2): string {
  const shadows: string[] = [];
  for (let x = -radius; x <= radius; x++) {
    for (let y = -radius; y <= radius; y++) {
      if (x === 0 && y === 0) continue;
      shadows.push(`${x}px ${y}px 0 ${color}`);
    }
  }
  return shadows.join(", ");
}

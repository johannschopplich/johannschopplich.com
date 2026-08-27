/** Warm orange brand color. The hue stays at 39 across every step. */
const primary = {
  50: "oklch(97% 0.0145 39)",
  100: "oklch(94% 0.028 39)",
  200: "oklch(89% 0.0491 39)",
  300: "oklch(83% 0.0785 39)",
  400: "oklch(75% 0.117 39)",
  500: "oklch(65% 0.1624 39)",
  600: "oklch(55% 0.143 39)",
  700: "oklch(46% 0.1172 39)",
  800: "oklch(38% 0.0938 39)",
  900: "oklch(31% 0.0731 39)",
  950: "oklch(21% 0.0549 39)",
};

/**
 * Muted olive green. The hue drifts from 120 to 132 across the dark half of the
 * scale; without that shift the darkest steps read as plain gray.
 */
const secondary = {
  50: "oklch(97% 0.016 120)",
  100: "oklch(94% 0.0258 120)",
  200: "oklch(89% 0.0388 120)",
  300: "oklch(83% 0.0546 120)",
  400: "oklch(75% 0.0729 120)",
  500: "oklch(65% 0.0926 120)",
  600: "oklch(55% 0.0815 123)",
  700: "oklch(46% 0.0668 126)",
  800: "oklch(38% 0.0535 128)",
  900: "oklch(31% 0.0417 130)",
  950: "oklch(21% 0.0313 132)",
};

/** Tailwind's stone scale, copied verbatim so the neutrals match preset-wind4. */
const stone = {
  50: "oklch(0.985 0.001 106.423)",
  100: "oklch(0.97 0.001 106.424)",
  200: "oklch(0.923 0.003 48.717)",
  300: "oklch(0.869 0.005 56.366)",
  400: "oklch(0.709 0.01 56.259)",
  500: "oklch(0.553 0.013 58.071)",
  600: "oklch(0.444 0.011 73.639)",
  700: "oklch(0.374 0.01 67.558)",
  800: "oklch(0.268 0.007 34.298)",
  900: "oklch(0.216 0.006 56.043)",
  950: "oklch(0.147 0.004 49.25)",
};

/** Swatches, keyed by step. What a color is, before anything decides what it is for. */
export const palette = { primary, secondary, stone };

/**
 * Roles, keyed by the variant that selects them – what a color is for, either
 * pointing at a swatch or standing on its own.
 *
 * These are the only tokens that have to leave CSS, since `background` doubles as
 * the `theme-color` meta value. That is why the generated JSON carries hex at all.
 */
export const colors = {
  background: {
    light: "oklch(99.1% 0.006 85)",
    dark: stone[900],
  },
};

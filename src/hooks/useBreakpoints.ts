import { theme } from "@unocss/preset-wind";

// eslint-disable-next-line @typescript-eslint/no-non-null-assertion
const breakpoints = Object.freeze(theme.breakpoints!);

/**
 * Indicates if the viewport is above the given breakpoint
 */
function isAbove(breakpoint: string): boolean {
  if (!(breakpoint in breakpoints)) {
    throw new Error(`Unknown breakpoint "${breakpoint}"`);
  }

  return matchMedia(`(min-width: ${breakpoints[breakpoint]})`).matches;
}

/**
 * Indicates if the viewport is below the given breakpoint
 */
function isBelow(breakpoint: string): boolean {
  return !isAbove(breakpoint);
}

export const useBreakpoints = () => ({
  breakpoints,
  isAbove,
  isBelow,
});

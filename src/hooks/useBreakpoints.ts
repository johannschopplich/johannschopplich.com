import { theme } from "@unocss/preset-wind";

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

export function useBreakpoints() {
  return {
    breakpoints,
    isAbove,
    isBelow,
  };
}

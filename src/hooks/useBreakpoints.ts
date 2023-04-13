export const breakpoints = {
  sm: "640px",
  md: "768px",
  lg: "1024px",
  xl: "1280px",
  "2xl": "1536px",
} as const;

export type Breakpoint = keyof typeof breakpoints;

/**
 * Indicates if the viewport is above the given breakpoint
 */
function isAbove(breakpoint: Breakpoint): boolean {
  if (!(breakpoint in breakpoints)) {
    throw new Error(`Unknown breakpoint "${breakpoint}"`);
  }

  return matchMedia(`(min-width: ${breakpoints[breakpoint]})`).matches;
}

/**
 * Indicates if the viewport is below the given breakpoint
 */
function isBelow(breakpoint: Breakpoint): boolean {
  return !isAbove(breakpoint);
}

export function useBreakpoints() {
  return {
    breakpoints,
    isAbove,
    isBelow,
  };
}

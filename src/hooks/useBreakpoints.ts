export const breakpoints = {
  sm: "640px",
  md: "768px",
  lg: "1024px",
  xl: "1280px",
  "2xl": "1536px",
};

/**
 * Indicates if the viewport is above the given breakpoint
 */
function isAbove(breakpoint: keyof typeof breakpoints): boolean {
  if (!(breakpoint in breakpoints)) {
    throw new Error(`Unknown breakpoint "${breakpoint}"`);
  }

  return matchMedia(`(min-width: ${breakpoints[breakpoint]})`).matches;
}

/**
 * Indicates if the viewport is below the given breakpoint
 */
function isBelow(breakpoint: keyof typeof breakpoints): boolean {
  return !isAbove(breakpoint);
}

export function useBreakpoints() {
  return {
    breakpoints,
    isAbove,
    isBelow,
  };
}

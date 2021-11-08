type Breakpoint = "xs" | "sm" | "md" | "lg" | "xl";

const breakpoints: Record<Breakpoint, number> = {
  xs: 0,
  sm: 640,
  md: 768,
  lg: 1024,
  xl: 1280,
};

/**
 * Indicates if the viewport is above the given breakpoint
 */
function isAbove(breakpoint: Breakpoint): boolean {
  if (!(breakpoint in breakpoints)) {
    throw new Error(`${breakpoint} is not a valid breakpoint`);
  }

  return matchMedia(`(min-width: ${breakpoints[breakpoint]}px)`).matches;
}

/**
 * Indicates if the viewport is below the given breakpoint
 */
function isBelow(breakpoint: Breakpoint): boolean {
  return !isAbove(breakpoint);
}

export default () => ({
  breakpoints,
  isAbove,
  isBelow,
});

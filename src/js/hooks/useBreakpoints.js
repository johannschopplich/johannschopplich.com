const breakpoints = {
  xs: 0,
  sm: 640,
  md: 768,
  lg: 1024,
  xl: 1280,
};

/**
 * Indicates if the viewport is above the given breakpoint
 *
 * @param {string} breakpoint Avaliable breakpoint
 * @returns {boolean} True if above
 */
function isAbove(breakpoint) {
  if (!(breakpoint in breakpoints)) {
    throw new Error(`${breakpoint} is not a valid breakpoint`);
  }

  return matchMedia(`(min-width: ${breakpoints[breakpoint]}px)`).matches;
}

/**
 * Indicates if the viewport is below the given breakpoint
 *
 * @param {string} breakpoint Avaliable breakpoint
 * @returns {boolean} True if below
 */
function isBelow(breakpoint) {
  return !isAbove(breakpoint);
}

export default () => ({
  breakpoints,
  isAbove,
  isBelow,
});

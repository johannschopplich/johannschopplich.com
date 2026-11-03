import { breakpoints } from "../breakpoints.ts";

export type Breakpoint = keyof typeof breakpoints;

export function isAbove(breakpoint: Breakpoint): boolean {
  if (!(breakpoint in breakpoints)) {
    throw new Error(`Unknown breakpoint "${breakpoint}"`);
  }

  return matchMedia(`(min-width: ${breakpoints[breakpoint]}px)`).matches;
}

export function isBelow(breakpoint: Breakpoint): boolean {
  return !isAbove(breakpoint);
}

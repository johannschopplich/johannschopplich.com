export function useRem() {
  return parseFloat(getComputedStyle(document.documentElement).fontSize);
}

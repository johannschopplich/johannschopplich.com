export function getRootFontSize() {
  return Number.parseFloat(getComputedStyle(document.documentElement).fontSize);
}

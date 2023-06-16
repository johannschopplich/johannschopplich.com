export function getRootFontSize() {
  return parseFloat(getComputedStyle(document.documentElement).fontSize);
}

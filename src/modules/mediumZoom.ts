import { useBreakpoints } from "../hooks";

export async function install() {
  const { isBelow } = useBreakpoints();
  const elements = [
    ...document.querySelectorAll<HTMLElement>("[data-zoomable]"),
  ];

  if (isBelow("md") || elements.length === 0) return;

  const { default: mediumZoom } = await import("medium-zoom");
  const margin = getRem();

  mediumZoom(elements, {
    background: "var(--du-color-background)",
    margin,
  });
}

function getRem() {
  return parseFloat(getComputedStyle(document.documentElement).fontSize);
}

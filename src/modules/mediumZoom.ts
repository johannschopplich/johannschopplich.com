import { useBreakpoints, useRem } from "../hooks";

export async function install() {
  const { isBelow } = useBreakpoints();
  const elements = [
    ...document.querySelectorAll<HTMLElement>("[data-zoomable]"),
  ];

  if (isBelow("md") || elements.length === 0) return;

  const { default: mediumZoom } = await import("medium-zoom");
  const margin = useRem();

  mediumZoom(elements, {
    background: "transparent",
    margin,
  });
}

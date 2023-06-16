import { getRootFontSize, isBelow } from "../utils";

export async function install() {
  const elements = [
    ...document.querySelectorAll<HTMLElement>("[data-zoomable]"),
  ];

  if (isBelow("md") || elements.length === 0) return;

  const { default: mediumZoom } = await import("medium-zoom");
  const margin = getRootFontSize();

  mediumZoom(elements, {
    background: "transparent",
    margin,
  });
}

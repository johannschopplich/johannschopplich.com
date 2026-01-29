import { getRootFontSize, isBelow } from "../utils";
import "medium-zoom/dist/style.css";

export async function install() {
  const elements = [
    ...document.querySelectorAll<HTMLElement>("[data-zoomable]"),
  ];

  if (isBelow("md") || elements.length === 0) return;

  const { default: mediumZoom } = await import("medium-zoom/dist/pure");
  const margin = getRootFontSize();

  mediumZoom(elements, {
    background: "transparent",
    margin,
  });
}

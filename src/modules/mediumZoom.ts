import { useBreakpoints } from "../hooks";

const selector = "[data-zoomable]";

export const install = async () => {
  const elements = [...document.querySelectorAll<HTMLElement>(selector)];

  const { isBelow } = useBreakpoints();
  if (isBelow("md") || elements.length === 0) return;

  const { default: mediumZoom } = await import("medium-zoom");
  const margin = parseFloat(
    getComputedStyle(document.body).getPropertyValue("font-size")
  );

  mediumZoom(elements, {
    background: "var(--color-background)",
    margin,
  });
};

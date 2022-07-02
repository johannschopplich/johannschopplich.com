import { useBreakpoints } from "../hooks";

export const install = async () => {
  const { isBelow } = useBreakpoints();
  const elements = [
    ...document.querySelectorAll<HTMLElement>("[data-zoomable]"),
  ];

  if (isBelow("md") || elements.length === 0) return;

  const { default: mediumZoom } = await import("medium-zoom");
  const margin = parseFloat(
    getComputedStyle(document.body).getPropertyValue("font-size")
  );

  mediumZoom(elements, {
    background: "var(--du-color-background)",
    margin,
  });
};

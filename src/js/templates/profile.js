import { useBreakpoints } from "../hooks";

export default async () => {
  const { isBelow } = useBreakpoints();
  if (isBelow("md")) return;

  const { createDrauu } = await import("drauu");

  createDrauu({
    el: ".bio-draw-area",
    brush: {
      mode: "stylus",
      color: "var(--color-accent)",
      size: 3,
    },
  });
};

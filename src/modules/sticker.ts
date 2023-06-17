import { isBelow } from "../utils";

export function install() {
  if (isBelow("md")) return;

  const elements = [...document.querySelectorAll<HTMLElement>(".sticker")];
  if (!elements.length) return;

  let latestMouseEvent: MouseEvent | undefined;

  const updateLightPosition = () => {
    if (!latestMouseEvent) return;

    for (const element of elements) {
      const container = element.querySelector<HTMLElement>("svg")!;
      const light = element.querySelector<HTMLElement>(".sticker-light");

      if (!container || !light) return;

      // Calculate the center point of the SVG
      const { top, left, width, height } = container.getBoundingClientRect();
      const centerX = left + width / 2;
      const centerY = top + height / 2;

      const dx = Math.ceil(latestMouseEvent.clientX - centerX);
      const dy = Math.ceil(latestMouseEvent.clientY - centerY);

      light.setAttribute("x", `${dx}`);
      light.setAttribute("y", `${dy}`);
    }

    latestMouseEvent = undefined;
  };

  const handleMouseMove = (event: MouseEvent) => {
    latestMouseEvent = event;
    requestAnimationFrame(updateLightPosition);
  };

  window.addEventListener("mousemove", handleMouseMove, {
    passive: true,
  });
}

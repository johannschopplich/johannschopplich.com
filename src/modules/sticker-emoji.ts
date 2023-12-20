import { isBelow } from "../utils";

let latestMouseEvent: MouseEvent | undefined;
let animationId: number | undefined;

export function install() {
  if (isBelow("md")) return;

  const elements = [
    ...document.querySelectorAll<HTMLElement>('[data-sticker="emoji"]'),
  ];
  if (!elements.length) return;

  const handleMouseMove = (event: MouseEvent) => {
    if (animationId) {
      // If an animation is running, cancel it
      cancelAnimationFrame(animationId);
      animationId = undefined;
    }

    latestMouseEvent = event;
    requestAnimationFrame(() => updateLightPosition(elements));
  };

  window.addEventListener("mousemove", handleMouseMove, { passive: true });
}

function updateLightPosition(elements: HTMLElement[]) {
  if (!latestMouseEvent) return;

  for (const element of elements) {
    const container = element.querySelector<HTMLElement>("svg");
    const light = element.querySelector<HTMLElement>("[data-sticker-light]");

    if (container && light) {
      const { top, left, width, height } = container.getBoundingClientRect();
      const centerX = left + width / 2;
      const centerY = top + height / 2;

      const dx = Math.ceil(latestMouseEvent.clientX - centerX);
      const dy = Math.ceil(latestMouseEvent.clientY - centerY);

      light.setAttribute("x", dx.toFixed(2));
      light.setAttribute("y", dy.toFixed(2));
    }
  }

  latestMouseEvent = undefined;
}

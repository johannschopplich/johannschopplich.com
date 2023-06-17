import { isBelow } from "../utils";

let animationId: number | undefined;
let latestMouseEvent: MouseEvent | undefined;
let startTime: number | undefined;

export function install() {
  if (isBelow("md")) return;

  const elements = [...document.querySelectorAll<HTMLElement>(".sticker")];
  if (!elements.length) return;

  const rotateLight = (timestamp: number) => {
    const duration = 1000;
    startTime ??= timestamp;

    // Calculate eased progress
    const t = Math.min((timestamp - startTime) / duration, 1);
    const easedT = easeOutCubic(t);
    const dx = 200 * Math.cos(2 * Math.PI * easedT);
    const dy =
      (easedT <= 0.5 ? -1 : 1) * 200 * Math.abs(Math.sin(2 * Math.PI * easedT));

    for (const element of elements) {
      const light = element.querySelector<HTMLElement>(".sticker-light");
      if (!light) return;

      light.setAttribute("x", dx.toFixed(2));
      light.setAttribute("y", dy.toFixed(2));
    }

    // Request next frame or stop when done
    if (t < 1) {
      animationId = requestAnimationFrame(rotateLight);
    } else {
      startTime = undefined;
      animationId = undefined;
    }
  };

  const handleMouseMove = (event: MouseEvent) => {
    if (animationId) {
      // If an animation is running, cancel it
      cancelAnimationFrame(animationId);
      animationId = undefined;
    }

    latestMouseEvent = event;
    requestAnimationFrame(updateLightPosition);
  };

  window.addEventListener("mousemove", handleMouseMove, {
    passive: true,
  });

  window.addEventListener("DOMContentLoaded", () => {
    if (!animationId) {
      // If not already animating, start
      animationId = requestAnimationFrame(rotateLight);
    }
  });

  function updateLightPosition() {
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

      light.setAttribute("x", dx.toFixed(2));
      light.setAttribute("y", dy.toFixed(2));
    }

    latestMouseEvent = undefined;
  }
}

function easeOutCubic(t: number): number {
  return --t * t * t + 1;
}

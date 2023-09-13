import { isBelow } from "../utils";

let latestMouseEvent: MouseEvent | undefined;
let animationId: number | undefined;
let startTime: number | undefined;

const duration = 750;

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

  // window.addEventListener("DOMContentLoaded", () => {
  //   animationId ??= requestAnimationFrame((ts) => rotateLight(ts, elements));
  // });
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

function rotateLight(timestamp: number, elements: HTMLElement[]) {
  startTime ??= timestamp;

  const t = Math.min((timestamp - startTime) / duration, 1);
  const easedT = easeOutCubic(t);
  const dx = 200 * Math.cos(2 * Math.PI * easedT);
  const dy =
    (easedT <= 0.5 ? -1 : 1) * 200 * Math.abs(Math.sin(2 * Math.PI * easedT));

  for (const element of elements) {
    const light = element.querySelector<HTMLElement>("[data-sticker-light]");
    if (light) {
      light.setAttribute("x", dx.toFixed(2));
      light.setAttribute("y", dy.toFixed(2));
    }
  }

  // Request next frame or stop when done
  if (t < 1) {
    animationId = requestAnimationFrame((ts) => rotateLight(ts, elements));
  } else {
    startTime = undefined;
    animationId = undefined;
  }
}

function easeOutCubic(t: number): number {
  return --t * t * t + 1;
}

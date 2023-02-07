import { Draggable } from "@neodrag/vanilla";

export default async () => {
  const element = document.querySelector<HTMLElement>("[data-draggable]");
  if (!element) return;

  const dragPosition = { x: 0, y: 0 };
  const easeOutCubic = (t: number) => --t * t * t + 1;
  const backdropFilter = document.querySelector<HTMLElement>(
    "[data-draggable-backdrop]"
  );

  const dragInstance = new Draggable(element, {
    // bounds: "parent",
    position: dragPosition,
    onDragStart() {
      backdropFilter?.removeAttribute("hidden");
    },
    onDrag({ offsetX, offsetY }) {
      dragPosition.x = offsetX;
      dragPosition.y = offsetY;
    },
    onDragEnd() {
      const targetX = 0;
      const targetY = 0;
      const duration = 2000;
      const startTime = performance.now();

      backdropFilter?.setAttribute("hidden", "");

      function updatePosition(time: number) {
        const elapsed = time - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easeProgress = easeOutCubic(progress);

        dragPosition.x =
          dragPosition.x + (targetX - dragPosition.x) * easeProgress;
        dragPosition.y =
          dragPosition.y + (targetY - dragPosition.y) * easeProgress;

        dragInstance.updateOptions({ position: dragPosition });

        if (
          progress < 1 &&
          // Threshold to prevent infinite loop
          (Math.abs(dragPosition.x - targetX) > 0.0001 ||
            Math.abs(dragPosition.y - targetY) > 0.0001)
        ) {
          requestAnimationFrame(updatePosition);
        }
      }

      requestAnimationFrame(updatePosition);
    },
  });
};

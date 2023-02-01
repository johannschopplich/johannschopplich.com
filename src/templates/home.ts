export default async () => {
  const { Draggable } = await import("@neodrag/vanilla");
  const element = document.querySelector<HTMLElement>(".draggable");
  if (!element) return;

  const dragPosition = { x: 0, y: 0 };
  const easingExpoOut = (t: number) => 1 - Math.pow(2, -10 * t);

  const dragInstance = new Draggable(element, {
    // bounds: "parent",
    position: dragPosition,
    onDrag({ offsetX, offsetY }) {
      dragPosition.x = offsetX;
      dragPosition.y = offsetY;
    },
    onDragEnd() {
      const targetX = 0;
      const targetY = 0;
      const duration = 2000;
      const startTime = performance.now();

      function updatePosition(time: number) {
        const elapsed = time - startTime;
        const progress = Math.min(elapsed / duration, 1);
        dragPosition.x =
          (1 - easingExpoOut(progress)) * dragPosition.x +
          easingExpoOut(progress) * targetX;
        dragPosition.y =
          (1 - easingExpoOut(progress)) * dragPosition.y +
          easingExpoOut(progress) * targetY;

        dragInstance.updateOptions({ position: dragPosition });

        if (
          progress < 1 &&
          // Prevents the animation from running forever
          Math.abs(dragPosition.x - targetX) > 0.00001 &&
          Math.abs(dragPosition.y - targetY) > 0.00001
        ) {
          requestAnimationFrame(updatePosition);
        }
      }

      requestAnimationFrame(updatePosition);
    },
  });
};

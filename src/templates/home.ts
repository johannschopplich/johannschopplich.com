export default async function () {
  const elements = [
    ...document.querySelectorAll<HTMLElement>('[data-sticker="svg"]'),
  ];
  if (elements.length === 0) return;

  for (const [index, element] of elements.entries()) {
    const animationDuration = Number.parseInt(
      getPropertyValue(element, "--un-animated-duration"),
      10,
    );

    if (index === 0) {
      setTimeout(() => {
        animateBounce(element, animationDuration);
      }, 100);
    }
  }
}

function animateBounce(element: HTMLElement, duration = 1000) {
  return new Promise<void>((resolve) => {
    // Trigger reflow to restart the animation
    void element.offsetWidth;

    element.classList.add("animated", "animated-tada");
    setTimeout(() => {
      element.classList.remove("animated", "animated-tada");
      resolve();
    }, duration);
  });
}

function getPropertyValue(element: HTMLElement, property: string) {
  return getComputedStyle(element).getPropertyValue(property).trim();
}

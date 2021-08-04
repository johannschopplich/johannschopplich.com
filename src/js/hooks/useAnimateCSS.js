/**
 * Animates an element with `Animate.css`
 *
 * @param {HTMLElement} element The element to animate
 * @param {string} animation The `Animate.css` animation class name (without prefix)
 * @param {string} [prefix="animate__"] `Animate.css` global class prefix
 * @returns {Promise<void>} Resolves when the animation has finished
 */
export default function (element, animation, prefix = "animate__") {
  return new Promise((resolve) => {
    const animations = [`${prefix}animated`, `${prefix}${animation}`];
    element.classList.add(...animations);

    // Clean classes and resolve Promise when the animation ends
    element.addEventListener(
      "animationend",
      () => {
        element.classList.remove(...animations);
        resolve();
      },
      { once: true }
    );
  });
}

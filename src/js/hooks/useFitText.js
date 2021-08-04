const slugify = (string) =>
  string
    .toLowerCase()
    .replace(/\s+/g, "-")
    .replace(/[^\w-]+/g, "");

/**
 * Fits text inside a container
 *
 * @param {string} [selector="[data-fit-text]"] Selector for elements to fit
 * @param {boolean} [autoWidth=false] Indicates to use parent container's width
 * @description
 * Note: Adjust the `--fit-text-scale` depending on your font.
 */
export default function (selector = "[data-fit-text]", autoWidth = false) {
  const styleId = slugify(selector);
  const elements = document.querySelectorAll(selector);

  if (!document.getElementById(styleId)) {
    const styleEl = document.createElement("style");
    styleEl.id = styleId;
    styleEl.innerHTML = `
      [data-fit-text] {
        font-size: calc(
          var(--fit-text-width, 100vw) /
          (var(--fit-text-length, 1) * 0.5) *
          var(--fit-text-scale, 1)
        );
      }
    `
      .replace(/\s\s+/g, " ")
      .trim();
    document.head.appendChild(styleEl);
  }

  for (const element of elements) {
    if (!element.dataset.fitText) element.dataset.fitText = "true";
    element.style.setProperty("--fit-text-length", element.innerText.length);

    if (autoWidth) {
      const { width } = element.parentElement.getBoundingClientRect();
      element.style.setProperty("--fit-text-width", `${width}px`);
    }
  }
}

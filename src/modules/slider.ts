import { useRem } from "../hooks";

export async function install() {
  const elements = document.querySelectorAll<HTMLElement>("[data-slider]");
  if (elements.length === 0) return;

  if (matchMedia("(hover: none), (pointer: coarse)").matches) return;

  const { default: Swiper } = await import("swiper");
  const rem = useRem();

  for (const element of elements) {
    // Remove classes that are interfering with Swiper.js
    element.firstElementChild?.classList.remove(
      "gap-xs",
      "snap-x",
      "overflow-x-auto"
    );

    new Swiper(element, {
      slidesPerView: "auto",
      spaceBetween: 0.75 * rem,
      centeredSlides: true,
      centeredSlidesBounds: true,
      grabCursor: true,
    });
  }
}

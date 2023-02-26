import Animere from "animere";
import { useRem } from "../hooks";

export async function install() {
  const isTouchscreen = matchMedia("(hover: none), (pointer: coarse)").matches;
  const rem = useRem();

  const elements = document.querySelectorAll<HTMLElement>("[data-slider]");
  if (elements.length === 0) return;

  // Check if image is outside viewport width and if so,
  // skip initializing Animere on it
  for (const slide of document.querySelectorAll<HTMLElement>(
    "[data-animere-slide]"
  )) {
    const { left } = slide.getBoundingClientRect();
    if (left > window.innerWidth) {
      slide.removeAttribute("data-animere-slide");
    }
  }

  // Animate slides within viewport
  new Animere({
    prefix: "animere-slide",
    initResolver: () => !!document.documentElement.dataset.animatable,
  });

  // Use scroll snap slider for mobile devices
  if (isTouchscreen) return;

  const { default: Swiper } = await import("swiper");

  for (const element of elements) {
    // Remove classes that are interfering with Swiper.js
    element.firstElementChild?.setAttribute("class", "swiper-wrapper");

    new Swiper(element, {
      slidesPerView: "auto",
      spaceBetween: 0.75 * rem,
      centeredSlides: true,
      centeredSlidesBounds: true,
      grabCursor: true,
      longSwipesRatio: 0.25,
    });
  }
}

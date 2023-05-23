import { animate } from "animere";
import { useRem } from "../hooks";

export async function install() {
  const isTouchscreen = matchMedia("(hover: none), (pointer: coarse)").matches;
  const rem = useRem();

  const elements = document.querySelectorAll<HTMLElement>("[data-slider]");
  if (elements.length === 0) return;

  // Use scroll snap slider for mobile devices
  if (!isTouchscreen) {
    // @ts-expect-error: types couldn't be resolved
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

  if (!("animatable" in document.documentElement.dataset)) return;

  const observer = new IntersectionObserver(
    (entries) => {
      for (const entry of entries) {
        if (!entry.isIntersecting) continue;

        for (const slide of entry.target.querySelectorAll<HTMLElement>(
          "[data-slide-content]"
        )) {
          slide.classList.remove("invisible");
          animate(slide, "fadeInLeft", "animate__");
        }
      }
    },
    { threshold: 0.1 }
  );

  for (const element of elements) {
    const rect = element.getBoundingClientRect();
    // If the slider is not initially in viewport, hide slides and start observer
    if (rect.top >= window.innerHeight || rect.bottom <= 0) {
      for (const slide of element.querySelectorAll<HTMLElement>(
        "[data-slide-content]"
      )) {
        slide.classList.add("invisible");
      }
      observer.observe(element);
    }
  }
}

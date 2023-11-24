import { animate } from "animere";
import { lazyLoad } from "unlazy";
import { getRootFontSize } from "../utils";

export async function install() {
  const isTouchscreen = matchMedia("(hover: none), (pointer: coarse)").matches;
  const fontSize = getRootFontSize();

  const elements = document.querySelectorAll<HTMLElement>("[data-slider]");
  if (elements.length === 0) return;

  // Use scroll snap slider for mobile devices
  if (isTouchscreen) {
    lazyLoad("[data-slider] [data-slide-image]");
  } else {
    const { default: Swiper } = await import("swiper");

    for (const element of elements) {
      // Remove classes that are interfering with Swiper.js
      element.firstElementChild?.setAttribute("class", "swiper-wrapper");

      // eslint-disable-next-line no-new
      new Swiper(element, {
        slidesPerView: "auto",
        spaceBetween: 0.75 * fontSize,
        centeredSlides: true,
        centeredSlidesBounds: true,
        grabCursor: true,
        longSwipesRatio: 0.25,
        on: {
          afterInit() {
            const images =
              element.querySelectorAll<HTMLImageElement>("[data-slide-image]");
            lazyLoad(images);
          },
        },
      });
    }
  }

  // Skip animations if reduced motion is enabled
  if (!("animatable" in document.documentElement.dataset)) return;

  const observer = new IntersectionObserver(
    ([entry]) => {
      if (!entry.isIntersecting) return;

      for (const slide of entry.target.querySelectorAll<HTMLElement>(
        "[data-slide-content]",
      )) {
        if (slide.classList.contains("invisible")) {
          slide.classList.remove("invisible");
          animate(slide, "fadeInLeft", "animate__");
        }
      }
    },
    { threshold: 0.25 },
  );

  // If the slider is not initially in viewport, hide slides and start observer
  for (const element of elements) {
    const rect = element.getBoundingClientRect();
    if (rect.top >= window.innerHeight || rect.bottom <= 0) {
      for (const slide of element.querySelectorAll<HTMLElement>(
        "[data-slide-content]",
      )) {
        slide.classList.add("invisible");
      }
      observer.observe(element);
    }
  }
}

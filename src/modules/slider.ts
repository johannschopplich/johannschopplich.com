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
    return;
  }

  const { default: Swiper } = await import("swiper");

  for (const element of elements) {
    // Remove classes that are interfering with Swiper.js
    element.firstElementChild?.setAttribute("class", "swiper-wrapper");

    // eslint-disable-next-line no-new
    new Swiper(element, {
      slidesPerView: "auto",
      spaceBetween: 0.75 * fontSize,
      // centeredSlides: true,
      // centeredSlidesBounds: true,
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

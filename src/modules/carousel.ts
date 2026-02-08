import type { EmblaCarouselType } from "embla-carousel";
import EmblaCarousel from "embla-carousel";
import { triggerLoad } from "unlazy";
import { isAbove } from "../utils/breakpoints";

declare global {
  interface Window {
    __carousels: EmblaCarouselType[];
  }
}

export function install() {
  const carousels: EmblaCarouselType[] = [];

  for (const node of document.querySelectorAll<HTMLElement>(
    "[data-carousel]",
  )) {
    carousels.push(setupCarousel(node));
  }

  if (import.meta.env.DEV) {
    window.__carousels = carousels;
  }
}

function setupCarousel(node: HTMLElement) {
  const emblaApi = EmblaCarousel(node, {
    align: "center",
    containScroll: "trimSnaps",
    skipSnaps: true,
    dragFree: isAbove("md"),
  });

  node.addEventListener("keydown", (event) => {
    if (event.key === "ArrowLeft") {
      event.preventDefault();
      emblaApi.goToPrev();
    } else if (event.key === "ArrowRight") {
      event.preventDefault();
      emblaApi.goToNext();
    }
  });

  // Note: Carousel images must NOT use native `loading="lazy"` to prevent:
  // alt text flash → layout shift → Embla `reinit` → scroll interruption
  emblaApi.on("slidesinview", (api, event) => {
    const slides = api.slideNodes();

    for (const index of event.detail.slidesEnterView) {
      const image =
        slides[index]!.querySelector<HTMLImageElement>("img[data-srcset]");
      if (image) triggerLoad(image);
    }
  });

  return emblaApi;
}

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
    align: "start",
    containScroll: "trimSnaps",
    skipSnaps: true,
    dragFree: isAbove("md"),
  });

  node.addEventListener("keydown", (event) => {
    if (event.key === "ArrowLeft") {
      event.preventDefault();
      emblaApi.scrollPrev();
    } else if (event.key === "ArrowRight") {
      event.preventDefault();
      emblaApi.scrollNext();
    }
  });

  // Lazy load visible slides on init and scroll
  lazyLoadImages(emblaApi);
  emblaApi.on("slidesInView", lazyLoadImages);

  return emblaApi;
}

function lazyLoadImages(emblaApi: EmblaCarouselType) {
  const slides = emblaApi.slideNodes();
  const inView = emblaApi.slidesInView();

  for (const index of inView) {
    const slide = slides[index];
    const images = slide!.querySelectorAll<HTMLImageElement>(
      'img[loading="lazy"]',
    );

    for (const image of images) triggerLoad(image);
  }
}

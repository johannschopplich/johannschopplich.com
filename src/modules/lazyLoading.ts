import { lazyLoad } from "unlazy";

export function install() {
  lazyLoad(
    // Exclude slider items, since loading event won't fire
    // before Swiper.js is initialized
    'img[loading="lazy"]:not([data-slide-image])',
  );
}

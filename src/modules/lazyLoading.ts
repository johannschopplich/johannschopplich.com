import { lazyLoad } from "unlazy";

export function install() {
  lazyLoad(
    // Exclude slider items (loading event won't fire)
    'img[loading="lazy"]:not([srcset])',
  );
}

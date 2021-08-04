import { useLazyload } from "../hooks";

const SELECTOR = "[data-lazyload]";

const observer = useLazyload(SELECTOR, {
  loaded: (element) => {
    if (import.meta.env.DEV) {
      console.log("Lazily loaded image:", element);
    }
  },
});

observer.observe();

// Preload all images
for (const image of document.querySelectorAll(SELECTOR)) {
  observer.triggerLoad(image);
}

import { useDebounceFn } from "./";

const validAttributes = ["data-src", "data-srcset"];
const isCrawler =
  !("onscroll" in window) ||
  /(gle|ing|ro)bot|crawl|spider/i.test(navigator.userAgent);

const load = (element) => {
  const newSrc = element.dataset.src;
  if (newSrc) element.src = newSrc;

  const newSrcset = element.dataset.srcset;
  if (newSrcset) {
    element.srcset = newSrcset;

    const newSizes = element.dataset.sizes;
    if (newSizes) {
      element.sizes =
        newSizes === "auto" ? `${element.offsetWidth}px` : newSizes;
    }
  }

  element.dataset.loaded = "true";
};

const isLoaded = (element) => element.dataset.loaded === "true";

const onIntersection = (loaded) => (entries, observer) => {
  for (const entry of entries) {
    if (entry.intersectionRatio > 0 || entry.isIntersecting) {
      const { target } = entry;
      observer.unobserve(target);

      if (isLoaded(target)) continue;
      load(target);
      loaded(target);
    }
  }
};

const onMutation = (entries) => {
  for (const entry of entries) {
    if (
      isLoaded(entry.target) &&
      entry.type === "attributes" &&
      validAttributes.includes(entry.attributeName)
    ) {
      load(entry.target);
    }
  }
};

const recalcSizes = (elements) => {
  for (const element of elements) {
    if (element.dataset.sizes === "auto") {
      element.sizes = `${element.offsetWidth}px`;
    }
  }
};

const getElements = (selector, root = document) => {
  if (selector instanceof Element) return [selector];
  if (selector instanceof NodeList) return [...selector];
  return root.querySelectorAll(selector);
};

/**
 * Lazily loads images SEO-friendly
 *
 * @param {(string|HTMLElement|NodeList)} [selector="[data-lazyload]"] Optional custom selector, element or node list
 * @param {object} [options={}] Optional default options
 * @returns {object} Object containing `observe` & `triggerLoad` methods and initialized observers
 */
export default function (selector = "[data-lazyload]", options = {}) {
  const {
    root,
    rootMargin = "0px",
    threshold = 0,
    enableAutoReload = false,
    loaded = () => {},
  } = options;

  const observer = new IntersectionObserver(onIntersection(loaded), {
    root,
    rootMargin,
    threshold,
  });

  let mutationObserver;
  if (enableAutoReload) {
    mutationObserver = new MutationObserver(onMutation);
  }

  return {
    observe() {
      const elements = getElements(selector, root);

      for (const element of elements) {
        if (isLoaded(element)) continue;

        if (isCrawler) {
          load(element);
          loaded(element);
          continue;
        }

        if (mutationObserver) {
          mutationObserver.observe(element, {
            subtree: true,
            attributes: true,
            attributeFilter: validAttributes,
          });
        }

        observer.observe(element);
      }

      const debounced = useDebounceFn(() => recalcSizes(elements), 100);
      window.addEventListener("resize", debounced);
    },

    triggerLoad(element) {
      if (isLoaded(element)) return;

      load(element);
      loaded(element);
    },

    observer,
    mutationObserver,
  };
}

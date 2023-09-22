import { getParsedSiteData } from "../utils";

const cache = new Map<string, string>();
let isFetching = false;

export default async function () {
  const elements = [
    ...document.querySelectorAll<HTMLElement>('[data-sticker="svg"]'),
  ];
  if (elements.length === 0) return;

  for (const [index, element] of elements.entries()) {
    let animationPromise: Promise<void> | undefined;
    const animationDuration = Number.parseInt(
      getPropertyValue(element, "--un-animated-duration"),
      10,
    );

    // Handle clicks on the sticker
    element.addEventListener("click", async () => {
      if (isFetching) return;
      isFetching = true;

      if (animationPromise) {
        await animationPromise;
      }

      const newSvgString = await fetchRandomSvg();
      if (!newSvgString) {
        isFetching = false;
        return;
      }

      const svg = element.querySelector<SVGElement>("svg");
      const parser = new DOMParser();
      const newSvgDoc = parser.parseFromString(newSvgString, "image/svg+xml");
      const newSvg = newSvgDoc.querySelector("svg");

      // Replace the SVG with a random one
      if (svg && newSvg) {
        element.replaceChild(newSvg, svg);
        animationPromise = animateBounce(element, animationDuration);
      }

      isFetching = false;
    });

    if (index === 0) {
      setTimeout(() => {
        animateBounce(element, animationDuration);
      }, 100);
    }
  }
}

async function fetchRandomSvg() {
  const { icons } = getParsedSiteData<{ icons: string[] }>();

  // Create list of icons that haven't been shown yet
  const iconsToBeShown = icons.filter((icon) => !cache.has(icon));

  if (iconsToBeShown.length > 0) {
    // If there are icons that have not yet been shown, prefer those
    const randomIndex = Math.floor(Math.random() * iconsToBeShown.length);
    const randomIcon = iconsToBeShown[randomIndex];
    return await fetchAndCacheSvg(randomIcon);
  } else {
    // If all icons have been shown, fetch any at random
    const randomIndex = Math.floor(Math.random() * icons.length);
    const randomIcon = icons[randomIndex];
    return cache.get(randomIcon)!;
  }
}

async function fetchAndCacheSvg(icon: string) {
  try {
    const response = await fetch(`/assets/icons/${icon}`);
    if (!response.ok) {
      throw new Error(response.statusText);
    }

    const svg = await response.text();
    cache.set(icon, svg);
    return svg;
  } catch (error) {
    console.error(`Failed to fetch SVG: ${error}`);
  }
}

function animateBounce(element: HTMLElement, duration = 1000) {
  return new Promise<void>((resolve) => {
    // Trigger reflow to restart the animation
    void element.offsetWidth;

    element.classList.add("animated", "animated-tada");
    setTimeout(() => {
      element.classList.remove("animated", "animated-tada");
      resolve();
    }, duration);
  });
}

function getPropertyValue(element: HTMLElement, property: string) {
  return getComputedStyle(element).getPropertyValue(property).trim();
}

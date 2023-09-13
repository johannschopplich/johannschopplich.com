import { getParsedSiteData } from "../utils";

const cache = new Map<string, string>();
let isFetching = false;

export default async function () {
  const elements = [
    ...document.querySelectorAll<HTMLElement>('[data-sticker="svg"]'),
  ];
  if (elements.length === 0) return;

  for (const element of elements) {
    let animationPromise: Promise<void> | undefined;

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
        animationPromise = animateBounce(element);
      }

      isFetching = false;
    });
  }
}

async function fetchRandomSvg() {
  const { icons } = getParsedSiteData();
  const randomIndex = Math.floor(Math.random() * icons.length);
  const randomIcon = icons[randomIndex];

  if (cache.has(randomIcon)) {
    return cache.get(randomIcon)!;
  }

  try {
    const response = await fetch(`/assets/img/icons/${randomIcon}`);
    if (!response.ok) {
      throw new Error(response.statusText);
    }

    const svg = await response.text();
    cache.set(randomIcon, svg);
    return svg;
  } catch (error) {
    console.error(`Failed to fetch SVG: ${error}`);
  }
}

function animateBounce(element: HTMLElement, duration = 1125) {
  return new Promise<void>((resolve) => {
    // Trigger reflow to restart the animation
    void element.offsetWidth;

    element.classList.add("animate-bounce");
    setTimeout(() => {
      element.classList.remove("animate-bounce");
      resolve();
    }, duration);
  });
}

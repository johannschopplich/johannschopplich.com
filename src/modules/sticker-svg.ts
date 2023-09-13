import { getParsedSiteData } from "../utils";

const cache = new Map<string, string>();

export function install(): void {
  const elements = [
    ...document.querySelectorAll<HTMLElement>('[data-sticker="svg"]'),
  ];
  if (elements.length === 0) return;

  // Handle clicks on the sticker
  document.addEventListener("click", async (event) => {
    const target = event.target as HTMLElement;
    const container = target.closest<HTMLElement>('[data-sticker="svg"]');

    if (container) {
      const svg = container.querySelector<SVGElement>("svg");
      const newSvgString = await fetchRandomSvg();
      const parser = new DOMParser();
      const newSvgDoc = parser.parseFromString(newSvgString, "image/svg+xml");
      const newSvg = newSvgDoc.querySelector("svg");

      // Replace the SVG with a random one
      if (svg && newSvg) {
        container.replaceChild(newSvg, svg);
      }
    }
  });
}

async function fetchRandomSvg() {
  const { icons } = getParsedSiteData();
  const randomIndex = Math.floor(Math.random() * icons.length);
  const randomIcon = icons[randomIndex];

  if (cache.has(randomIcon)) {
    return cache.get(randomIcon)!;
  }

  const response = await fetch(`/assets/img/icons/${randomIcon}`);
  if (!response.ok) {
    console.error(`Failed to fetch SVG: ${response.status}`);
  }

  const svg = await response.text();

  cache.set(randomIcon, svg);
  return svg;
}

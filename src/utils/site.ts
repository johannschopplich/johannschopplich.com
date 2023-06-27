import { destr } from "destr";

export function getParsedSiteData(selector = "[data-site]") {
  const rawData = document.querySelector<HTMLElement>(selector)?.textContent;
  const data = destr<Record<string, any>>(rawData) || {};
  return Object.freeze(data);
}

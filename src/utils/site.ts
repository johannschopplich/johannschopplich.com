import { tryParseJSON } from "@byjohann/utils";

export function getParsedSiteData<
  T extends Record<string, any> = Record<string, any>,
>(selector = "[data-site]") {
  const rawData = document.querySelector<HTMLElement>(selector)?.textContent;
  const data = tryParseJSON<T>(rawData) || {};
  return Object.freeze(data);
}

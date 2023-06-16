import destr from "destr";

export function getParsedSiteData(selector = "[data-site]") {
  const rawData = document.querySelector<HTMLElement>(selector)?.textContent;
  const data: Record<string, any> = destr(rawData) || {};
  return Object.freeze(data);
}

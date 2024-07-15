export function getParsedSiteData<
  T extends Record<string, any> = Record<string, any>,
>(selector = "[data-site]") {
  const rawData = document.querySelector<HTMLElement>(selector)?.textContent;
  const data = tryParseJson<T>(rawData) || {};
  return Object.freeze(data);
}

function tryParseJson<T = unknown>(value: unknown): T {
  if (typeof value !== "string") {
    return value as T;
  }

  try {
    return JSON.parse(value);
  } catch {
    return value as T;
  }
}

import destr from "destr";

export function useSiteData() {
  const rawData = document.getElementById("site-data")?.textContent;
  const data: Record<string, any> = destr(rawData) || {};
  return Object.freeze(data);
}

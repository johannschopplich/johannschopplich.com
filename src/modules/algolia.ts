import { useSiteData } from "../hooks";

export async function install() {
  const container = document.querySelector("[data-docsearch]");
  if (!container) return;

  const { algolia } = useSiteData();
  const { default: docsearch } = await import("@docsearch/js");
  const { locales } = await import("../locales/algolia");
  const lang = document.documentElement.lang as keyof typeof locales;

  docsearch({
    ...algolia,
    container,
    indexName: `johannschopplich-${lang}`,
    placeholder: {
      en: "Search Site",
      de: "Seite durchsuchen",
    }?.[lang],
    translations: locales?.[lang],
  });

  const docsearchButton = document.querySelector(".DocSearch");
  localStorage.setItem(
    "algolia.docsearch.rect",
    JSON.stringify(docsearchButton?.getBoundingClientRect() ?? {})
  );
}

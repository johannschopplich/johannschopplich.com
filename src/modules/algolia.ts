import { getParsedSiteData } from "../utils";

export async function install() {
  const container = document.querySelector("[data-docsearch]");
  if (!container) return;

  const { algolia } = getParsedSiteData();
  const { default: DocSearch } = await import("@docsearch/js");
  const { locales } = await import("../locales/algolia");
  const lang = document.documentElement.lang as keyof typeof locales;

  document
    .querySelector<HTMLButtonElement>(".DocSearch-Button")
    ?.addEventListener("click", () => {
      DocSearch({
        ...algolia,
        container,
        indexName: `johannschopplich-${lang}`,
        placeholder: {
          en: "Search Site",
          de: "Seite durchsuchen",
        }[lang],
        translations: locales[lang],
        // insights: true,
      });

      // Simulate click on the button to open the search modal
      document.querySelector<HTMLElement>(".DocSearch-Button")?.click();
    });
}

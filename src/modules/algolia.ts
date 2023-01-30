import { useSiteData } from "../hooks";

export async function install() {
  const { algolia } = useSiteData();
  const lang = document.documentElement.lang as keyof typeof translations;
  const container = document.querySelector("[data-docsearch]");
  if (!container) return;

  const { default: docsearch } = await import("@docsearch/js");

  docsearch({
    ...algolia,
    container,
    indexName: `johannschopplich-${lang}}`,
    placeholder: {
      en: "Search Site",
      de: "Seite durchsuchen",
    }?.[lang],
    translations: translations?.[lang],
  });

  const docsearchButton = document.querySelector(".DocSearch");
  if (docsearchButton) {
    localStorage.setItem(
      "algolia.docsearch.rect",
      JSON.stringify(docsearchButton.getBoundingClientRect())
    );
  }
}

const translations = {
  en: {
    button: {
      buttonText: "Search",
      buttonAriaLabel: "Search",
    },
    modal: {
      searchBox: {
        resetButtonTitle: "Clear the query",
        resetButtonAriaLabel: "Clear the query",
        cancelButtonText: "Cancel",
        cancelButtonAriaLabel: "Cancel",
      },
      startScreen: {
        recentSearchesTitle: "Recent",
        noRecentSearchesText: "No recent searches",
        saveRecentSearchButtonTitle: "Save this search",
        removeRecentSearchButtonTitle: "Remove this search from history",
        favoriteSearchesTitle: "Favorite",
        removeFavoriteSearchButtonTitle: "Remove this search from favorites",
      },
      errorScreen: {
        titleText: "Unable to fetch results",
        helpText: "You might want to check your network connection.",
      },
      footer: {
        selectText: "to select",
        selectKeyAriaLabel: "Enter key",
        navigateText: "to navigate",
        navigateUpKeyAriaLabel: "Arrow up",
        navigateDownKeyAriaLabel: "Arrow down",
        closeText: "to close",
        closeKeyAriaLabel: "Escape key",
        searchByText: "Search by",
      },
      noResultsScreen: {
        noResultsText: "No results for",
        suggestedQueryText: "Try searching for",
        reportMissingResultsText: "Believe this query should return results?",
        reportMissingResultsLinkText: "Let us know.",
      },
    },
  },
  de: {
    button: {
      buttonText: "Suchen",
      buttonAriaLabel: "Suchen",
    },
    modal: {
      searchBox: {
        resetButtonTitle: "Suche zurücksetzen",
        resetButtonAriaLabel: "Suche zurücksetzen",
        cancelButtonText: "Abbrechen",
        cancelButtonAriaLabel: "Abbrechen",
      },
      startScreen: {
        recentSearchesTitle: "Kürzlich gesucht",
        noRecentSearchesText: "Keine kürzlichen Suchen",
        saveRecentSearchButtonTitle: "Diese Suche speichern",
        removeRecentSearchButtonTitle: "Diese Suche aus dem Verlauf entfernen",
        favoriteSearchesTitle: "Favoriten",
        removeFavoriteSearchButtonTitle:
          "Diese Suche aus den Favoriten entfernen",
      },
      errorScreen: {
        titleText: "Ergebnisse konnten nicht abgerufen werden",
        helpText: "Überprüfe deine Netzwerkverbindung.",
      },
      footer: {
        selectText: "auswählen",
        selectKeyAriaLabel: "Enter-Taste",
        navigateText: "navigieren",
        navigateUpKeyAriaLabel: "Pfeil nach oben",
        navigateDownKeyAriaLabel: "Pfeil nach unten",
        closeText: "schließen",
        closeKeyAriaLabel: "Escape-Taste",
        searchByText: "Suche durch",
      },
      noResultsScreen: {
        noResultsText: "Keine Ergebnisse für",
        suggestedQueryText: "Versuche nach",
        reportMissingResultsText:
          "Glaubst du, dass diese Suche Ergebnisse liefern sollte?",
        reportMissingResultsLinkText: "Lass es uns wissen.",
      },
    },
  },
};

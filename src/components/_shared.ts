export const SUPPORTS_CONSTRUCTABLE_STYLESHEETS =
  "replaceSync" in CSSStyleSheet.prototype;

/**
 * Apply CSS to a shadow root using constructable stylesheets when available
 */
export function adoptStyles(
  shadowRoot: ShadowRoot,
  css: string,
  sharedSheet?: CSSStyleSheet | HTMLStyleElement,
): CSSStyleSheet | HTMLStyleElement {
  if (sharedSheet) {
    if (SUPPORTS_CONSTRUCTABLE_STYLESHEETS) {
      shadowRoot.adoptedStyleSheets = [sharedSheet as CSSStyleSheet];
    } else {
      shadowRoot.append((sharedSheet as HTMLStyleElement).cloneNode(true));
    }
    return sharedSheet;
  }

  // Create new stylesheet
  if (SUPPORTS_CONSTRUCTABLE_STYLESHEETS) {
    const sheet = new CSSStyleSheet();
    sheet.replaceSync(css);
    shadowRoot.adoptedStyleSheets = [sheet];
    return sheet;
  }

  const styleElement = document.createElement("style");
  styleElement.textContent = css;
  shadowRoot.append(styleElement);
  return styleElement;
}

export const prefersReducedMotion = matchMedia(
  "(prefers-reduced-motion: reduce)",
);

import { debounce } from "es-toolkit";

let sheet: CSSStyleSheet | HTMLStyleElement;
const supportsConstructableStylesheets =
  "replaceSync" in CSSStyleSheet.prototype;

export class MasonryGrid extends HTMLElement {
  #gap: number = 16;
  #columns: number = 0;
  #needsUpdate: boolean = false;
  #resizeObserver: ResizeObserver;

  #css = `
:host {
  display: grid;
  grid-template-columns: repeat(
    auto-fit,
    minmax(min(var(--masonry-column-max-width, 25rem), 100%), 1fr)
  );
  justify-content: center;
  gap: var(--masonry-gap, 16px);
}

::slotted(*) {
  align-self: flex-start;
}
`;

  constructor() {
    super();
    this.#resizeObserver = new ResizeObserver(this.debouncedUpdateGridItems);
  }

  generateCss() {
    if (!sheet) {
      if (supportsConstructableStylesheets) {
        sheet = new CSSStyleSheet();
        sheet.replaceSync(this.#css);
      } else {
        sheet = document.createElement("style");
        sheet.textContent = this.#css;
      }
    }

    if (supportsConstructableStylesheets) {
      this.shadowRoot!.adoptedStyleSheets = [sheet as CSSStyleSheet];
    } else {
      this.shadowRoot!.append((sheet as HTMLStyleElement).cloneNode(true));
    }
  }

  connectedCallback() {
    if (!this.shadowRoot) {
      this.attachShadow({ mode: "open" });
      this.generateCss();
      this.shadowRoot!.append(document.createElement("slot"));
    }

    this.#gap = Number.parseFloat(getComputedStyle(this).rowGap);

    // Observe the container itself
    this.#resizeObserver.observe(this);

    // Observe all children
    for (const child of [...this.children]) {
      this.#resizeObserver.observe(child);
    }

    this.updateGridItems();
  }

  disconnectedCallback() {
    this.#resizeObserver.disconnect();
  }

  debouncedUpdateGridItems = debounce(() => {
    this.updateGridItems();
  }, 100);

  updateGridItems() {
    const columns =
      getComputedStyle(this).gridTemplateColumns.split(" ").length;
    const items = [...this.children] as HTMLElement[];

    // Check if any items have changed height
    for (const item of items) {
      const { height } = item.getBoundingClientRect();
      const currentHeight = height.toString();

      if (currentHeight !== item.dataset.h) {
        item.dataset.h = currentHeight;
        this.#needsUpdate = true;
      }
    }

    // Bail if the number of columns hasn't changed and no heights changed
    if (this.#columns === columns && !this.#needsUpdate) return;

    // Update the number of columns
    this.#columns = columns;

    // Revert to initial positioning and remove margin
    items.forEach((item) => item.style.removeProperty("margin-top"));

    // Apply masonry layout if we have more than one column
    if (this.#columns > 1) {
      // Skip the first row of items
      for (const [index, item] of items.slice(columns).entries()) {
        const itemAbove = items[index];
        // Bottom edge of item above
        const prevBottom = itemAbove.getBoundingClientRect().bottom;
        // Top edge of current item
        const currentTop = item.getBoundingClientRect().top;

        // Set margin to create the masonry effect
        const marginTop = prevBottom + this.#gap - currentTop;
        item.style.marginTop = `${marginTop}px`;
      }
    }

    this.#needsUpdate = false;
  }
}

export function setup() {
  customElements.define("masonry-grid", MasonryGrid);
}

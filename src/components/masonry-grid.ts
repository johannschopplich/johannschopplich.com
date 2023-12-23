import { debounce } from "../utils";

let masonrySheet: CSSStyleSheet | HTMLStyleElement;
const supportsConstructableStylesheets =
  "replaceSync" in CSSStyleSheet.prototype;

export class MasonryGrid extends HTMLElement {
  #gap: number = 16;
  #columns: number = 0;
  #count: number = 0;

  #css = `
:host {
  display: grid;
  grid-template-columns: repeat(
    auto-fit,
    minmax(min(var(--masonry-column-max-width, 25rem), 100%), 1fr)
  );
  justify-content: center;
  gap: var(--masonry-grid-gap, 16px);
}

::slotted(*) {
  align-self: flex-start;
}
`;

  generateCss() {
    if (!masonrySheet) {
      if (supportsConstructableStylesheets) {
        masonrySheet = new CSSStyleSheet();
        masonrySheet.replaceSync(this.#css);
      } else {
        masonrySheet = document.createElement("style");
        masonrySheet.textContent = this.#css;
      }
    }

    if (supportsConstructableStylesheets) {
      this.shadowRoot!.adoptedStyleSheets = [masonrySheet as CSSStyleSheet];
    } else {
      this.shadowRoot!.append(
        (masonrySheet as HTMLStyleElement).cloneNode(true),
      );
    }
  }

  connectedCallback() {
    if (!this.shadowRoot) {
      this.attachShadow({ mode: "open" });
      this.generateCss();
      this.shadowRoot!.append(document.createElement("slot"));
    }

    this.#gap = Number.parseFloat(getComputedStyle(this).rowGap);
    this.updateGridItems();
    window.addEventListener("resize", this.debouncedUpdateGridItems);
  }

  disconnectedCallback() {
    window.removeEventListener("resize", this.debouncedUpdateGridItems);
  }

  debouncedUpdateGridItems = debounce(() => {
    this.updateGridItems();
  }, 100);

  updateGridItems() {
    const columns =
      getComputedStyle(this).gridTemplateColumns.split(" ").length;
    const items = [...this.children] as HTMLElement[];

    for (const item of items) {
      const { height } = item.getBoundingClientRect();
      const h = height.toString();

      if (h !== item.dataset.h) {
        item.dataset.h = h;
        this.#count++;
      }
    }

    // Bail if the number of columns hasn't changed
    if (this.#columns === columns && !this.#count) return;

    // Update the number of columns
    this.#columns = columns;

    // Revert to initial positioning and remove margin
    for (const { style } of items) style.removeProperty("margin-top");

    if (this.#columns > 1) {
      for (const [index, item] of items.slice(columns).entries()) {
        // Bottom edge of item above
        const { bottom: prevBottom } = items[index].getBoundingClientRect();
        // Top edge of current item
        const { top } = item.getBoundingClientRect();

        item.style.marginTop = `${prevBottom + this.#gap - top}px`;
      }
    }

    this.#count = 0;
  }
}

export function setup() {
  customElements.define("masonry-grid", MasonryGrid);
}

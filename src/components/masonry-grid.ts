import { adoptStyles } from "./_shared";

let sharedSheet: CSSStyleSheet | HTMLStyleElement | undefined;

export class MasonryGrid extends HTMLElement {
  #gap: number = 16;
  #columns: number = 0;
  #needsUpdate: boolean = false;
  #resizeObserver: ResizeObserver;
  #itemHeights = new WeakMap<HTMLElement, number>();

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
    this.#resizeObserver = new ResizeObserver(this.updateGridItems.bind(this));
  }

  connectedCallback() {
    if (!this.shadowRoot) {
      this.attachShadow({ mode: "open" });
      sharedSheet = adoptStyles(this.shadowRoot!, this.#css, sharedSheet);
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

  updateGridItems() {
    // Get current column count from computed style
    const columns =
      getComputedStyle(this).gridTemplateColumns.split(" ").length;
    const items = [...this.children] as HTMLElement[];

    let hasHeightChanges = false;

    // Check each item for height changes
    for (const item of items) {
      const height = Math.round(item.getBoundingClientRect().height);
      const previousHeight = this.#itemHeights.get(item);

      if (previousHeight === undefined || previousHeight !== height) {
        hasHeightChanges = true;
        this.#itemHeights.set(item, height);
      }
    }

    // Bail if the number of columns hasn't changed and no heights have changed
    if (this.#columns === columns && !hasHeightChanges && !this.#needsUpdate)
      return;

    // Update the number of columns
    this.#columns = columns;

    // Revert to initial positioning and reset all item margins
    requestAnimationFrame(() => {
      items.forEach((item) => item.style.removeProperty("margin-top"));

      // Apply masonry layout if we have more than one column
      if (this.#columns > 1) {
        // Slight delay to ensure the margin removal has been applied
        requestAnimationFrame(() => {
          // Skip the first row of items
          for (const [index, item] of items.slice(columns).entries()) {
            const itemAbove = items[index];
            if (!itemAbove) continue;
            // Bottom edge of item above
            const prevBottom = itemAbove.getBoundingClientRect().bottom;
            // Top edge of current item
            const currentTop = item.getBoundingClientRect().top;

            // Set margin to create the masonry effect
            item.style.marginTop = `${prevBottom + this.#gap - currentTop}px`;
          }

          this.#needsUpdate = false;
        });
      } else {
        this.#needsUpdate = false;
      }
    });
  }
}

export function setup() {
  customElements.define("masonry-grid", MasonryGrid);
}

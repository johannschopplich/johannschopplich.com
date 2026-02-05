import { adoptStyles } from "./_shared";

let sharedSheet: CSSStyleSheet | HTMLStyleElement | undefined;

export class MasonryGrid extends HTMLElement {
  #gap = 16;
  #columns = 0;
  #needsUpdate = false;
  #itemHeights = new WeakMap<HTMLElement, number>();
  #resizeObserver = new ResizeObserver(() => this.#updateGridItems());

  #css = `
:host {
  display: grid;
  grid-template-columns: repeat(
    auto-fit,
    minmax(min(var(--masonry-column-max-width, 25rem), 100%), 1fr)
  );
  justify-content: center;
}

::slotted(*) {
  align-self: flex-start;
}
`;

  connectedCallback() {
    if (!this.shadowRoot) {
      this.attachShadow({ mode: "open" });
      sharedSheet = adoptStyles(this.shadowRoot!, this.#css, sharedSheet);
      this.shadowRoot!.append(document.createElement("slot"));
    }

    this.#gap = Number.parseFloat(getComputedStyle(this).rowGap);
    this.#resizeObserver.observe(this);

    for (const child of [...this.children]) {
      this.#resizeObserver.observe(child);
    }

    this.#updateGridItems();
  }

  disconnectedCallback() {
    this.#resizeObserver.disconnect();
  }

  /**
   * Greedy bin packing: assign each item to the column with the shortest height.
   * Uses CSS `order` property to reorder items without DOM manipulation.
   */
  #optimizeOrder(items: HTMLElement[], columns: number): void {
    if (columns <= 1 || items.length <= columns) {
      for (const item of items) {
        item.style.removeProperty("order");
      }
      return;
    }

    const heights = items.map(
      (item) =>
        this.#itemHeights.get(item) ?? item.getBoundingClientRect().height,
    );

    // Greedy assignment: place each item in the shortest column
    const columnHeights = Array.from<number>({ length: columns }).fill(0);
    const columnItems: number[][] = Array.from({ length: columns }, () => []);

    for (let i = 0; i < items.length; i++) {
      const minCol = columnHeights.indexOf(Math.min(...columnHeights));
      columnItems[minCol]!.push(i);
      columnHeights[minCol]! += heights[i]! + this.#gap;
    }

    // Build new order: row by row across columns
    const maxRows = Math.max(...columnItems.map((c) => c.length));
    const newOrder: number[] = [];

    for (let row = 0; row < maxRows; row++) {
      for (const column of columnItems) {
        const itemIndex = column[row];
        if (itemIndex !== undefined) {
          newOrder.push(itemIndex);
        }
      }
    }

    // Apply CSS order property
    for (let position = 0; position < newOrder.length; position++) {
      const itemIndex = newOrder[position];
      if (itemIndex !== undefined) {
        items[itemIndex]!.style.order = String(position);
      }
    }
  }

  #updateGridItems() {
    const columns =
      getComputedStyle(this).gridTemplateColumns.split(" ").length;
    const items = [...this.children] as HTMLElement[];

    let hasHeightChanges = false;

    for (const item of items) {
      const height = Math.round(item.getBoundingClientRect().height);
      const previousHeight = this.#itemHeights.get(item);

      if (previousHeight === undefined || previousHeight !== height) {
        hasHeightChanges = true;
        this.#itemHeights.set(item, height);
      }
    }

    if (this.#columns === columns && !hasHeightChanges && !this.#needsUpdate) {
      return;
    }

    const hasColumnsChanged = this.#columns !== columns;
    this.#columns = columns;

    if (hasColumnsChanged) {
      this.#optimizeOrder(items, columns);
    }

    // Use nested rAF to ensure margin reset is applied before recalculating
    requestAnimationFrame(() => {
      for (const item of items) {
        item.style.removeProperty("margin-top");
      }

      if (this.#columns > 1) {
        requestAnimationFrame(() => {
          this.#applyMasonryMargins(items, columns);
          this.#needsUpdate = false;
        });
      } else {
        this.#needsUpdate = false;
      }
    });
  }

  #applyMasonryMargins(items: HTMLElement[], columns: number): void {
    const sortedItems = [...items].sort((a, b) => {
      const orderA = Number.parseInt(a.style.order || "0", 10);
      const orderB = Number.parseInt(b.style.order || "0", 10);
      return orderA - orderB;
    });

    // Skip the first row of items
    for (const [index, item] of sortedItems.slice(columns).entries()) {
      const itemAbove = sortedItems[index];
      if (!itemAbove) continue;

      const prevBottom = itemAbove.getBoundingClientRect().bottom;
      const currentTop = item.getBoundingClientRect().top;

      item.style.marginTop = `${prevBottom + this.#gap - currentTop}px`;
    }
  }
}

export function setup() {
  customElements.define("masonry-grid", MasonryGrid);
}

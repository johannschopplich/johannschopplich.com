import { debounce } from "@github/mini-throttle";

export default class {
  grids: {
    _el: HTMLElement;
    gap: number;
    items: HTMLElement[];
    ncol: number;
    mod: number;
  }[] = [];

  constructor() {
    const elements = [
      ...document.querySelectorAll<HTMLElement>(".grid-masonry"),
    ];

    // Bail if no elements where found
    if (elements.length === 0) return;

    // Bail if masonry layouts are already supported by the browser
    if (getComputedStyle(elements[0]).gridTemplateRows === "masonry") return;

    this.init(elements);
  }

  init(elements: HTMLElement[]) {
    this.grids = elements.map((grid) => ({
      _el: grid,
      gap: parseFloat(getComputedStyle(grid).rowGap),
      items: ([...grid.childNodes] as HTMLElement[])
        // Make sure the child nodes are element nodes
        .filter((c) => c.nodeType === 1)
        // Optionally stretch the first element across the grid
        .filter(
          (c) =>
            !grid.dataset.stretchFirstElement &&
            +getComputedStyle(c).gridColumnEnd !== -1
        ),
      ncol: 0,
      mod: 0,
    }));

    const debounced = debounce(() => this.calcLayout(), 100);
    window.addEventListener("resize", debounced);
    window.addEventListener("load", () => this.calcLayout());
  }

  calcLayout() {
    for (const grid of this.grids) {
      // Get the post relayout number of columns
      const ncol = getComputedStyle(grid._el).gridTemplateColumns.split(
        " "
      ).length;

      for (const c of grid.items) {
        const { height } = c.getBoundingClientRect();
        const h = height.toString();

        if (h !== c.dataset?.h) {
          c.dataset.h = h;
          grid.mod++;
        }
      }

      // If the number of columns has changed
      if (grid.ncol !== ncol || grid.mod) {
        // Update the number of columns
        grid.ncol = ncol;

        // Revert to initial positioning, no margin
        grid.items.forEach((c) => c.style.removeProperty("margin-top"));

        // If we have more than one column
        if (grid.ncol > 1) {
          grid.items.slice(ncol).forEach((c, i) => {
            // Bottom edge of item above
            const { bottom: prevBottom } =
              grid.items[i].getBoundingClientRect();
            // Top edge of current item
            const { top } = c.getBoundingClientRect();

            c.style.marginTop = `${prevBottom + grid.gap - top}px`;
          });
        }

        grid.mod = 0;
      }
    }
  }
}

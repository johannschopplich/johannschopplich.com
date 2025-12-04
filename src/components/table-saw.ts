// Forked from: https://github.com/zachleat/table-saw

import { SUPPORTS_CONSTRUCTABLE_STYLESHEETS } from "./_shared";

export class Tablesaw extends HTMLElement {
  static identifiers: Record<string, boolean> = {};

  _needsStylesheet = true;
  _identifier = "";
  attrs = {
    breakpoint: "breakpoint",
    breakpointBackwardsCompat: "media",
    type: "type",
    ratio: "ratio",
    label: "data-tablesaw-label",
    zeropad: "zero-padding",
    forceTextAlign: "text-align",
  };
  defaults = {
    breakpoint: "(max-width: 39.9375em)", // Same as Filament Group’s Tablesaw
    ratio: "1fr 2fr",
  };
  classes = {
    wrap: "tablesaw-wrap",
  };
  props = {
    ratio: "--table-saw-ratio",
    bold: "--table-saw-header-bold",
  };

  generateCss(breakpoint: string, type: string) {
    return `
table-saw.${this._identifier} {
	display: block;
	${type === "container" ? "container-type: inline-size;" : ""}
}

@${type} ${breakpoint} {
	table-saw.${this._identifier} thead :is(th, td) {
		position: absolute;
		height: 1px;
		width: 1px;
		overflow: hidden;
		clip: rect(1px, 1px, 1px, 1px);
	}
	table-saw.${this._identifier} :is(tbody, tfoot) tr {
		display: block;
	}
	table-saw.${this._identifier} :is(tbody, tfoot) :is(th, td):before {
		font-weight: var(${this.props.bold});
		content: attr(${this.attrs.label});
	}
	table-saw.${this._identifier} :is(tbody, tfoot) :is(th, td) {
		display: grid;
		gap: 0 1em;
		grid-template-columns: var(${this.props.ratio}, ${this.defaults.ratio});
	}
	table-saw.${this._identifier}[${
    this.attrs.forceTextAlign
  }] :is(tbody, tfoot) :is(th, td) {
		text-align: ${this.getAttribute(this.attrs.forceTextAlign) || "left"};
	}
	table-saw.${this._identifier}[${
    this.attrs.zeropad
  }] :is(tbody, tfoot) :is(th, td) {
		padding-left: 0;
		padding-right: 0;
	}
}`;
  }

  connectedCallback() {
    if (!SUPPORTS_CONSTRUCTABLE_STYLESHEETS) return;

    this.addHeaders();
    this.setRatio();

    if (!this._needsStylesheet) return;

    const sheet = new CSSStyleSheet();
    const breakpoint =
      this.getAttribute(this.attrs.breakpoint) ||
      this.getAttribute(this.attrs.breakpointBackwardsCompat) ||
      this.defaults.breakpoint;
    const type = this.getAttribute(this.attrs.type) || "media";

    this._identifier = `ts_${type.slice(0, 1)}${breakpoint.replace(
      /[^a-z0-9]/gi,
      "_",
    )}`;
    this.classList.add(this._identifier);

    if (!Tablesaw.identifiers[this._identifier]) {
      const css = this.generateCss(breakpoint, type);
      sheet.replaceSync(css);

      document.adoptedStyleSheets.push(sheet);

      Tablesaw.identifiers[this._identifier] = true;
    }
  }

  addHeaders() {
    const headerCells = this.querySelectorAll<HTMLTableCellElement>("thead th");
    const labels = Array.from(headerCells).map((cell, index) => {
      // Set headers to be bold (if headers are bold)
      if (index === 0) {
        const styles = getComputedStyle(cell);
        if (styles) {
          // Copy other styles?
          const bold = styles.getPropertyValue("font-weight");
          this.setBold(bold);
        }
      }

      return cell.textContent?.trim();
    });

    if (labels.length === 0) {
      this._needsStylesheet = false;
      console.error("No `<th>` elements for Tablesaw were found:", this);
      return;
    }

    const cells =
      this.querySelectorAll<HTMLTableCellElement>("tbody :is(td, th)");
    for (const cell of cells) {
      if (!labels[cell.cellIndex]) continue;

      cell.setAttribute(this.attrs.label, labels[cell.cellIndex]!);

      let nodeCount = 0;
      for (const n of cell.childNodes) {
        // Text or element node
        if (n.nodeType === 3 || n.nodeType === 1) {
          nodeCount++;
        }
      }

      // Wrap if this cell has child nodes for correct grid alignment
      if (nodeCount > 1) {
        const wrapper = document.createElement("div");
        wrapper.classList.add(this.classes.wrap);
        while (cell.firstChild) {
          wrapper.appendChild(cell.firstChild);
        }
        cell.appendChild(wrapper);
      }
    }
  }

  setBold(bold: string) {
    if (bold || bold === "") {
      this.style.setProperty(this.props.bold, bold);
    }
  }

  setRatio() {
    const ratio = this.getAttribute(this.attrs.ratio);
    if (ratio) {
      const ratioString = `${ratio.split("/").join("fr ")}fr`;
      this.style.setProperty(this.props.ratio, ratioString);
    }
  }
}

export function setup() {
  customElements.define("table-saw", Tablesaw);
}

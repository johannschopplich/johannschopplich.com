// Forked from: https://github.com/zachleat/snow-fall
export class Snow extends HTMLElement {
  static random(min: number, max: number) {
    return min + Math.floor(Math.random() * (max - min) + 1);
  }

  static attrs = {
    count: "count", // default: 100
    mode: "mode",
  };

  generateCss(mode: "page" | "element", count: number) {
    const css: string[] = [];
    css.push(`
:host([mode="element"]) {
	display: block;
	position: relative;
	overflow: hidden;
}
:host([mode="page"]) {
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
}
:host([mode="page"]),
:host([mode="element"]) > * {
	pointer-events: none;
}
:host([mode="element"]) ::slotted(*) {
	pointer-events: all;
}
* {
	position: absolute;
	width: var(--snow-fall-size, 10px);
	height: var(--snow-fall-size, 10px);
	background: var(--snow-fall-color, rgba(255,255,255,.5));
	border-radius: 50%;
}
`);

    // using vw units (max 100)
    let dimensions = { width: 100, height: 100 };
    let units = { x: "vw", y: "vh" };

    if (mode === "element" && this.firstElementChild) {
      dimensions = {
        width: this.firstElementChild.clientWidth,
        height: this.firstElementChild.clientHeight,
      };
      units = { x: "px", y: "px" };
    }

    // Thank you @alphardex: https://codepen.io/alphardex/pen/dyPorwJ
    for (let j = 1; j <= count; j++) {
      const x = (Snow.random(1, 100) * dimensions.width) / 100; // vw
      const offset = (Snow.random(-10, 10) * dimensions.width) / 100; // vw

      const yoyo = Math.round(Snow.random(30, 100)); // % time
      const yStart = (yoyo * dimensions.height) / 100; // vh
      const yEnd = dimensions.height; // vh

      const scale = Snow.random(1, 10000) * 0.0001;
      const duration = Snow.random(10, 30);
      const delay = Snow.random(0, 30) * -1;

      css.push(`
:nth-child(${j}) {
	opacity: ${Snow.random(0, 1000) * 0.001};
	transform: translate(${x}${units.x}, -10px) scale(${scale});
	animation: fall-${j} ${duration}s ${delay}s linear infinite;
}

@keyframes fall-${j} {
	${yoyo}% {
		transform: translate(${x + offset}${units.x}, ${yStart}${
      units.y
    }) scale(${scale});
	}

	to {
		transform: translate(${x + offset / 2}${units.x}, ${yEnd}${
      units.y
    }) scale(${scale});
	}
}`);
    }
    return css.join("\n");
  }

  connectedCallback() {
    // https://caniuse.com/mdn-api_cssstylesheet_replacesync
    if (this.shadowRoot || !("replaceSync" in CSSStyleSheet.prototype)) {
      return;
    }

    const count =
      Number.parseInt(this.getAttribute(Snow.attrs.count) ?? "") || 100;

    let mode: "page" | "element";
    if (this.hasAttribute(Snow.attrs.mode)) {
      mode = this.getAttribute(Snow.attrs.mode) as "page" | "element";
    } else {
      mode = this.firstElementChild ? "element" : "page";
      this.setAttribute(Snow.attrs.mode, mode);
    }

    const sheet = new CSSStyleSheet();
    sheet.replaceSync(this.generateCss(mode, count));

    const shadowroot = this.attachShadow({ mode: "open" });
    shadowroot.adoptedStyleSheets = [sheet];

    const d = document.createElement("div");
    for (let j = 0, k = count; j < k; j++) {
      shadowroot.appendChild(d.cloneNode());
    }

    shadowroot.appendChild(document.createElement("slot"));
  }
}

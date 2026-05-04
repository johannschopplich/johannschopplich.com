import {
  prefersReducedMotion,
  SUPPORTS_CONSTRUCTABLE_STYLESHEETS,
} from "./_shared";
import COMPONENT_CSS from "./scramble-text.css?raw";

type ScrambleFrom = "left" | "right" | "center" | "random";

interface Cell {
  el: HTMLSpanElement;
  target: string;
}

interface CellPlan extends Cell {
  start: number;
  end: number;
  scratch: string;
}

export interface ScrambleOptions {
  duration?: number;
  cursor?: string;
  from?: ScrambleFrom;
  perturbation?: number;
  settleDuration?: number;
  chars?: string;
  ease?: (t: number) => number;
  refreshRate?: number;
}

export interface SetupOptions {
  /** URL to a font that supplies the cursor block-shading glyphs (U+2591–2593). */
  cursorFontUrl?: string;
  /**
   * Family to register the cursor font under. Pick the family the host
   * already uses – the unicode-range registration adds a cursor-glyph
   * variant so cells inherit normally and only the cursor codepoints come
   * from the bundled font.
   */
  cursorFontFamily?: string;
  /** Font weight to register the cursor font at. Defaults to "600". */
  cursorFontWeight?: string;
}

const DEFAULTS: Required<ScrambleOptions> = {
  duration: 600,
  cursor: "░▒▓",
  from: "left",
  perturbation: 0.2,
  settleDuration: 250,
  chars: "abcdefghijklmnopqrstuvwxyz0123456789",
  // Quadratic ease-in-out
  ease: (t: number) => (t < 0.5 ? 2 * t * t : 1 - 2 * (1 - t) * (1 - t)),
  refreshRate: 30,
};

/**
 * Renders text as a row of fixed-width per-character cells and animates a
 * scramble reveal across them. Each cell's box is pinned (in `em`) to its
 * settled glyph's natural advance, so the scramble's per-frame char swaps
 * cannot shift any layout – neither siblings of the host nor cells within.
 *
 * Markup:
 * ```html
 * <scramble-text from="left">Hi, ich bin Johann.</scramble-text>
 * ```
 *
 * The host is `display: inline` and inherits font/color from its ancestor.
 * To upgrade the cursor block-shading glyphs (░▒▓), pass `cursorFontUrl` +
 * `cursorFontFamily` to `setup()`.
 */
export class ScrambleText extends HTMLElement {
  #cells: Cell[] = [];
  #originalText = "";
  #abort: AbortController | null = null;
  #built = false;

  static get observedAttributes() {
    return ["from", "duration", "cursor", "perturbation"];
  }

  connectedCallback() {
    if (!this.#built) {
      this.#originalText = (this.textContent ?? "").replace(/\s+/g, " ").trim();
      this.setAttribute("aria-label", this.#originalText);
    }
  }

  disconnectedCallback() {
    this.#abort?.abort();
  }

  /**
   * Resolves once the animation settles. Calling `play()` again while one
   * is running aborts the previous and starts a new one.
   */
  async play(options: ScrambleOptions = {}): Promise<void> {
    if (prefersReducedMotion.matches) {
      this.reset();
      return;
    }

    if (!this.#built) {
      await this.#build();
      await this.#measureCursorScale();
    }

    const resolvedOptions: Required<ScrambleOptions> = {
      ...DEFAULTS,
      from: (this.getAttribute("from") as ScrambleFrom | null) ?? DEFAULTS.from,
      duration: this.hasAttribute("duration")
        ? Number(this.getAttribute("duration"))
        : DEFAULTS.duration,
      cursor: this.getAttribute("cursor") ?? DEFAULTS.cursor,
      perturbation: this.hasAttribute("perturbation")
        ? Number(this.getAttribute("perturbation"))
        : DEFAULTS.perturbation,
      ...options,
    };

    this.#abort?.abort();
    const controller = new AbortController();
    this.#abort = controller;

    const cellCount = this.#cells.length;
    if (cellCount === 0) return Promise.resolve();

    return new Promise<void>((resolve) => {
      const settleRatio =
        resolvedOptions.settleDuration / resolvedOptions.duration;
      const settleSpacing = (1 - settleRatio) / cellCount;
      const cursorChars = resolvedOptions.cursor;
      const cursorLength = cursorChars.length;
      const cursorZone = cursorLength * settleSpacing;
      const order = buildOrder(cellCount, resolvedOptions.from);
      const charset = resolvedOptions.chars;
      const charsetLength = charset.length;
      const offsetScale =
        resolvedOptions.perturbation > 0
          ? resolvedOptions.perturbation * settleRatio
          : 0;

      // Each cell gets a small random offset on `start` and `end` so the
      // wave reads as organic rather than a perfect line. `scratch` seeds
      // the random char shown in the scramble phase before the first step
      // boundary refreshes it.
      const cellPlans: CellPlan[] = this.#cells.map((cell, i) => {
        const startOffset =
          offsetScale > 0 ? (Math.random() - 0.5) * 2 * offsetScale : 0;
        const endOffset =
          offsetScale > 0 ? (Math.random() - 0.5) * 2 * offsetScale : 0;
        const start = order[i]! * settleSpacing + startOffset;
        return {
          el: cell.el,
          target: cell.target,
          start,
          end: start + settleRatio + endOffset,
          scratch: charset[Math.floor(Math.random() * charsetLength)]!,
        };
      });

      // Refresh `scratch` only at step boundaries – gives the scramble
      // a discrete "ticking" cadence rather than a hyperactive per-frame
      // reroll.
      const stepRatio =
        1000 / (resolvedOptions.refreshRate * resolvedOptions.duration);
      let lastStep = -1;

      const startTime = performance.now();
      const tick = (now: number) => {
        if (controller.signal.aborted) return resolve();

        const progress = (now - startTime) / resolvedOptions.duration;
        if (progress >= 1) {
          this.#settle();
          return resolve();
        }

        const t = resolvedOptions.ease(progress);
        const currentStep = (progress / stepRatio) | 0;
        const shouldRefresh = currentStep !== lastStep;
        if (shouldRefresh) lastStep = currentStep;

        for (const cell of cellPlans) {
          let next: string;
          let isCursor = false;
          if (t >= cell.end || t < cell.start) {
            next = cell.target;
          } else if (t - cell.start < cursorZone) {
            const index = ((t - cell.start) / settleSpacing) | 0;
            next = cursorChars[cursorLength - 1 - index] ?? "";
            isCursor = true;
          } else {
            if (shouldRefresh) {
              cell.scratch =
                charset[Math.floor(Math.random() * charsetLength)]!;
            }
            next = cell.scratch;
          }

          if (cell.el.firstChild?.nodeValue !== next)
            cell.el.textContent = next;
          cell.el.classList.toggle("is-cursor", isCursor);
        }

        requestAnimationFrame(tick);
      };

      requestAnimationFrame(tick);
    });
  }

  reset() {
    if (!this.#built) return;
    this.#settle();
  }

  // Cell widths are measured in `em` against the inherited font; await the
  // load so we don't measure against a fallback that mismatches after swap.
  async #build() {
    const computedStyle = getComputedStyle(this);
    const fontShorthand = `${computedStyle.fontStyle} ${computedStyle.fontWeight} ${computedStyle.fontSize} ${computedStyle.fontFamily}`;

    try {
      await document.fonts.load(fontShorthand, this.#originalText);
    } catch {
      // Best-effort – fall through to measure with whatever's available
    }

    const fontSize = Number.parseFloat(computedStyle.fontSize);
    const cells: Cell[] = [];
    const fragment = document.createDocumentFragment();
    let currentWord: HTMLSpanElement | null = null;

    for (const character of [...this.#originalText]) {
      if (character === " ") {
        currentWord = null;
        fragment.appendChild(document.createTextNode(" "));
        continue;
      }
      if (!currentWord) {
        currentWord = document.createElement("span");
        currentWord.dataset.scrambleWord = "";
        fragment.appendChild(currentWord);
      }
      const el = document.createElement("span");
      el.dataset.scrambleCell = "";
      el.textContent = character;
      el.setAttribute("aria-hidden", "true");
      currentWord.appendChild(el);
      cells.push({ el, target: character });
    }

    this.replaceChildren(fragment);

    for (const cell of cells) {
      const px = cell.el.getBoundingClientRect().width;
      cell.el.style.setProperty("--cell-w", `${(px / fontSize).toFixed(4)}em`);
    }

    this.#cells = cells;
    this.#built = true;
  }

  // Block-fill cursor glyphs tend to be visually taller than letters.
  // Probe with "Hg" (cap-height + descender) and ▓ on a canvas, then expose
  // the ratio so the cell can shrink cursor chars to match the line's
  // visual mass. Force the cursor font to load before measuring – the
  // browser hasn't rendered its codepoints yet, so canvas would otherwise
  // fall back to a system font.
  async #measureCursorScale() {
    const probe = this.#cells[0]?.el;
    if (!probe) return;

    const computedStyle = getComputedStyle(probe);
    const font = `${computedStyle.fontStyle} ${computedStyle.fontWeight} ${computedStyle.fontSize} ${computedStyle.fontFamily}`;

    try {
      await document.fonts.load(font, "▓");
    } catch {
      // Best-effort – fall through to measure with whatever's available
    }

    const ctx = document.createElement("canvas").getContext("2d");

    if (!ctx) return;
    ctx.font = font;

    const hostMetrics = ctx.measureText("Hg");
    const cursorMetrics = ctx.measureText("▓");
    const hostHeight =
      hostMetrics.actualBoundingBoxAscent +
      hostMetrics.actualBoundingBoxDescent;
    const cursorHeight =
      cursorMetrics.actualBoundingBoxAscent +
      cursorMetrics.actualBoundingBoxDescent;

    if (!hostHeight || !cursorHeight) return;

    const scale = hostHeight / cursorHeight;
    if (Math.abs(scale - 1) > 0.02) {
      this.style.setProperty("--cursor-scale", scale.toFixed(4));
    }
  }

  #settle() {
    for (const cell of this.#cells) {
      if (cell.el.firstChild?.nodeValue !== cell.target)
        cell.el.textContent = cell.target;
      cell.el.classList.remove("is-cursor");
    }
  }
}

declare global {
  interface HTMLElementTagNameMap {
    "scramble-text": ScrambleText;
  }
}

export function setup(options: SetupOptions = {}) {
  injectStylesOnce();

  if (!customElements.get("scramble-text")) {
    customElements.define("scramble-text", ScrambleText);
  }

  if (options.cursorFontUrl && options.cursorFontFamily) {
    new FontFace(options.cursorFontFamily, `url(${options.cursorFontUrl})`, {
      unicodeRange: "U+2591-2593",
      weight: options.cursorFontWeight ?? "600",
      display: "swap",
    })
      .load()
      .then((loaded) => document.fonts.add(loaded))
      .catch(() => {
        // Best-effort – cells fall back to the inherited font's own glyphs
      });
  }
}

let hasInjectedStyles = false;
function injectStylesOnce() {
  if (hasInjectedStyles) return;
  hasInjectedStyles = true;

  if (SUPPORTS_CONSTRUCTABLE_STYLESHEETS) {
    const sheet = new CSSStyleSheet();
    sheet.replaceSync(COMPONENT_CSS);
    document.adoptedStyleSheets = [...document.adoptedStyleSheets, sheet];
  } else {
    const style = document.createElement("style");
    style.textContent = COMPONENT_CSS;
    document.head.append(style);
  }
}

function buildOrder(count: number, from: ScrambleFrom): Int32Array {
  const order = new Int32Array(count);

  if (from === "random") {
    const indices = Array.from({ length: count }, (_, i) => i);
    for (let i = count - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [indices[i], indices[j]] = [indices[j]!, indices[i]!];
    }
    for (let i = 0; i < count; i++) order[indices[i]!] = i;
    return order;
  }

  const pivotIndex =
    from === "right" ? count - 1 : from === "center" ? (count - 1) / 2 : 0;
  const indices = Array.from({ length: count }, (_, i) => i);
  indices.sort((a, b) => Math.abs(a - pivotIndex) - Math.abs(b - pivotIndex));

  for (let i = 0; i < count; i++) order[indices[i]!] = i;

  return order;
}

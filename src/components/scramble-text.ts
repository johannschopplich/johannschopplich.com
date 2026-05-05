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
  /**
   * Characters whose target glyph stays put for the whole animation –
   * they don't scramble, don't flash the cursor, and don't consume a slot
   * in the wave's timing.
   */
  ignore?: string;
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
  /**
   * Project-wide default for the per-element `ignore` attribute. Override
   * per element via `ignore="..."`; pass `ignore=""` to disable.
   */
  defaultIgnore?: string;
}

const DEFAULTS: Required<ScrambleOptions> = {
  duration: 600,
  cursor: "░▒▓",
  from: "left",
  perturbation: 0.2,
  settleDuration: 250,
  chars: "abcdefghijklmnopqrstuvwxyz0123456789",
  ignore: "",
  // Quadratic ease-in-out
  ease: (t: number) => (t < 0.5 ? 2 * t * t : 1 - 2 * (1 - t) * (1 - t)),
  refreshRate: 30,
};

// 4 em-decimals stays sub-pixel at any reasonable font size
const CSS_VALUE_PRECISION = 4;

// Below this, the cursor block already reads at letter height
const CURSOR_SCALE_THRESHOLD = 0.02;

interface FrameContext {
  cursorChars: string;
  cursorLength: number;
  cursorZone: number;
  settleSpacing: number;
  settleRatio: number;
  charset: string;
  charsetLength: number;
  offsetScale: number;
}

let setupDefaultIgnore = "";

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
    return ["from", "duration", "cursor", "perturbation", "ignore"];
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
      ignore: this.getAttribute("ignore") ?? setupDefaultIgnore,
      ...options,
    };

    this.#abort?.abort();
    const controller = new AbortController();
    this.#abort = controller;

    // Settle first so any leftover scrambled state from an aborted play
    // doesn't bleed into cells that are now ignored by the new options.
    this.#settle();

    const ignoreSet = new Set([...resolvedOptions.ignore]);
    const animatedCells = ignoreSet.size
      ? this.#cells.filter((cell) => !ignoreSet.has(cell.target))
      : this.#cells;

    if (animatedCells.length === 0) return;

    const ctx = buildFrameContext(resolvedOptions, animatedCells.length);
    const cellPlans = buildCellPlans(animatedCells, resolvedOptions.from, ctx);
    const stepRatio =
      1000 / (resolvedOptions.refreshRate * resolvedOptions.duration);

    return new Promise<void>((resolve) => {
      // Refresh `scratch` only at step boundaries – gives the scramble
      // a discrete "ticking" cadence rather than a hyperactive per-frame
      // reroll.
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
        const currentStep = Math.floor(progress / stepRatio);
        const shouldRefresh = currentStep !== lastStep;
        if (shouldRefresh) lastStep = currentStep;

        for (const plan of cellPlans) {
          const { nextChar, isCursor } = tickCell(t, plan, ctx, shouldRefresh);
          if (plan.el.firstChild?.nodeValue !== nextChar)
            plan.el.textContent = nextChar;
          plan.el.classList.toggle("is-cursor", isCursor);
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
    const fontShorthand = buildFontShorthand(computedStyle);

    try {
      await document.fonts.load(fontShorthand, this.#originalText);
    } catch {
      // Best-effort – fall through to measure with whatever's available
    }

    const { fragment, cells } = buildCellSpans(this.#originalText);
    this.replaceChildren(fragment);
    pinCellWidths(cells, Number.parseFloat(computedStyle.fontSize));

    this.#cells = cells;
    this.#built = true;
  }

  // Force the cursor font to load before probing – its codepoints haven't
  // been rendered yet, so canvas would otherwise fall back to a system font.
  async #measureCursorScale() {
    const probeCell = this.#cells[0]?.el;
    if (!probeCell) return;

    const fontShorthand = buildFontShorthand(getComputedStyle(probeCell));

    try {
      await document.fonts.load(fontShorthand, "▓");
    } catch {
      // Best-effort – fall through to measure with whatever's available
    }

    const scale = computeCursorScale(fontShorthand);
    if (scale && Math.abs(scale - 1) > CURSOR_SCALE_THRESHOLD) {
      this.style.setProperty(
        "--cursor-scale",
        scale.toFixed(CSS_VALUE_PRECISION),
      );
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

  if (options.defaultIgnore !== undefined) {
    setupDefaultIgnore = options.defaultIgnore;
  }

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

function tickCell(
  t: number,
  plan: CellPlan,
  ctx: FrameContext,
  shouldRefresh: boolean,
): { nextChar: string; isCursor: boolean } {
  if (t >= plan.end || t < plan.start) {
    return { nextChar: plan.target, isCursor: false };
  }

  if (t - plan.start < ctx.cursorZone) {
    const index = Math.floor((t - plan.start) / ctx.settleSpacing);
    return {
      nextChar: ctx.cursorChars[ctx.cursorLength - 1 - index] ?? "",
      isCursor: true,
    };
  }

  if (shouldRefresh) {
    plan.scratch = ctx.charset[Math.floor(Math.random() * ctx.charsetLength)]!;
  }

  return {
    nextChar: plan.scratch,
    isCursor: false,
  };
}

// Random per-cell start/end offsets keep the wave from reading as a
// perfect line.
function buildCellPlans(
  cells: Cell[],
  from: ScrambleFrom,
  ctx: FrameContext,
): CellPlan[] {
  const order = buildOrder(cells.length, from);
  return cells.map((cell, i) => {
    const startOffset =
      ctx.offsetScale > 0 ? (Math.random() - 0.5) * 2 * ctx.offsetScale : 0;
    const endOffset =
      ctx.offsetScale > 0 ? (Math.random() - 0.5) * 2 * ctx.offsetScale : 0;
    const start = order[i]! * ctx.settleSpacing + startOffset;
    return {
      el: cell.el,
      target: cell.target,
      start,
      end: start + ctx.settleRatio + endOffset,
      scratch: ctx.charset[Math.floor(Math.random() * ctx.charsetLength)]!,
    };
  });
}

function buildFrameContext(
  options: Required<ScrambleOptions>,
  cellCount: number,
): FrameContext {
  const settleRatio = options.settleDuration / options.duration;
  const settleSpacing = (1 - settleRatio) / cellCount;
  const cursorChars = options.cursor;
  const cursorLength = cursorChars.length;
  return {
    cursorChars,
    cursorLength,
    cursorZone: cursorLength * settleSpacing,
    settleSpacing,
    settleRatio,
    charset: options.chars,
    charsetLength: options.chars.length,
    offsetScale:
      options.perturbation > 0 ? options.perturbation * settleRatio : 0,
  };
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

// Cells must enter the DOM as plain inline spans for kerning to survive
// long enough to measure; the inline-block this promotion adds would
// otherwise close each glyph into its own formatting context first.
function pinCellWidths(cells: Cell[], fontSize: number) {
  const widths = cells.map((cell) => cell.el.getBoundingClientRect().width);
  for (let i = 0; i < cells.length; i++) {
    const cell = cells[i]!;
    cell.el.style.setProperty(
      "--cell-w",
      `${(widths[i]! / fontSize).toFixed(CSS_VALUE_PRECISION)}em`,
    );
    cell.el.dataset.scrambleCell = "";
  }
}

function buildCellSpans(text: string): {
  fragment: DocumentFragment;
  cells: Cell[];
} {
  const cells: Cell[] = [];
  const fragment = document.createDocumentFragment();
  let currentWord: HTMLSpanElement | null = null;

  for (const character of [...text]) {
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
    el.textContent = character;
    el.setAttribute("aria-hidden", "true");
    currentWord.appendChild(el);
    cells.push({ el, target: character });
  }

  return { fragment, cells };
}

// Block-fill glyphs tend to be visually taller than letters; probe with
// "Hg" (cap-height + descender) and ▓ to expose the ratio.
function computeCursorScale(fontShorthand: string): number | null {
  const canvasContext = document.createElement("canvas").getContext("2d");
  if (!canvasContext) return null;
  canvasContext.font = fontShorthand;

  const hostMetrics = canvasContext.measureText("Hg");
  const cursorMetrics = canvasContext.measureText("▓");
  const hostHeight =
    hostMetrics.actualBoundingBoxAscent + hostMetrics.actualBoundingBoxDescent;
  const cursorHeight =
    cursorMetrics.actualBoundingBoxAscent +
    cursorMetrics.actualBoundingBoxDescent;

  if (!hostHeight || !cursorHeight) return null;
  return hostHeight / cursorHeight;
}

function buildFontShorthand(style: CSSStyleDeclaration): string {
  return `${style.fontStyle} ${style.fontWeight} ${style.fontSize} ${style.fontFamily}`;
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

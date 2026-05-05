import {
  prefersReducedMotion,
  SUPPORTS_CONSTRUCTABLE_STYLESHEETS,
} from "./_shared";
import COMPONENT_CSS from "./scramble-text.css?raw";

type ScrambleOrigin = "left" | "right" | "center" | "random";

export interface ScrambleOptions {
  duration?: number;
  cursor?: string;
  from?: ScrambleOrigin;
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

interface WordRoot {
  wordEl: HTMLSpanElement;
  realEl: HTMLSpanElement;
  characters: string[];
  overlayEl?: HTMLSpanElement;
}

interface CharSlot {
  charEl: HTMLSpanElement;
  targetChar: string;
}

interface WaveContext {
  cursorChars: string;
  cursorLength: number;
  cursorZone: number;
  settleSpacing: number;
  settleRatio: number;
  charset: string;
  charsetLength: number;
  perturbationScale: number;
}

interface AnimationPlan {
  slot: CharSlot;
  startTime: number;
  endTime: number;
  scratchChar: string;
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

let setupDefaultIgnore = "";

/**
 * Renders text as a per-word stack: the natural word stays in flow as a
 * width anchor (visibility-hidden during animation) while an absolutely-
 * positioned overlay paints the scramble on top. Layout never moves – the
 * overlay is out of flow, and the real word's box is established at parse
 * time and never re-measured.
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
  #wordRoots: WordRoot[] = [];
  #abort: AbortController | null = null;
  #originalText = "";
  #isWrapped = false;

  static get observedAttributes() {
    return ["from", "duration", "cursor", "perturbation", "ignore"];
  }

  connectedCallback() {
    if (this.#isWrapped) return;

    this.#originalText = (this.textContent ?? "").replace(/\s+/g, " ").trim();
    this.setAttribute("aria-label", this.#originalText);

    const { fragment, wordRoots } = buildWordTree(this.#originalText);
    this.replaceChildren(fragment);
    this.#wordRoots = wordRoots;
    this.#isWrapped = true;
  }

  disconnectedCallback() {
    this.#abort?.abort();
    this.#abort = null;
  }

  /**
   * Resolves once the animation settles. Calling `play()` again while one
   * is running aborts the previous and starts a new one.
   */
  async play(options: ScrambleOptions = {}): Promise<void> {
    if (!this.#isWrapped) return;
    if (prefersReducedMotion.matches) {
      this.reset();
      return;
    }

    const resolvedOptions = resolveOptions(this, options);

    this.#abort?.abort();
    const controller = new AbortController();
    this.#abort = controller;

    await ensureCursorScale(this);
    if (controller.signal.aborted) return;

    unmountOverlays(this.#wordRoots);
    const slots = mountOverlays(this.#wordRoots);
    if (slots.length === 0) return;

    const ignoreSet = new Set([...resolvedOptions.ignore]);
    const animatedSlots = ignoreSet.size
      ? slots.filter((slot) => !ignoreSet.has(slot.targetChar))
      : slots;

    if (animatedSlots.length === 0) {
      unmountOverlays(this.#wordRoots);
      return;
    }

    const ctx = buildWaveContext(resolvedOptions, animatedSlots.length);
    const plans = buildAnimationPlans(animatedSlots, resolvedOptions.from, ctx);
    const stepRatio =
      1000 / (resolvedOptions.refreshRate * resolvedOptions.duration);

    return new Promise<void>((resolve) => {
      // Refresh `scratchChar` only at step boundaries – gives the scramble
      // a discrete "ticking" cadence rather than a hyperactive per-frame
      // reroll.
      let lastStep = -1;
      const startTime = performance.now();

      const tick = (now: number) => {
        if (controller.signal.aborted) return resolve();

        const progress = (now - startTime) / resolvedOptions.duration;
        if (progress >= 1) {
          unmountOverlays(this.#wordRoots);
          return resolve();
        }

        const easedTime = resolvedOptions.ease(progress);
        const currentStep = Math.floor(progress / stepRatio);
        const shouldRefresh = currentStep !== lastStep;
        if (shouldRefresh) lastStep = currentStep;

        for (const plan of plans) {
          renderPlan(plan, easedTime, ctx, shouldRefresh);
        }

        requestAnimationFrame(tick);
      };

      requestAnimationFrame(tick);
    });
  }

  reset() {
    this.#abort?.abort();
    this.#abort = null;
    if (this.#isWrapped) unmountOverlays(this.#wordRoots);
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

// --- Option resolution ---

function resolveOptions(
  el: HTMLElement,
  overrides: ScrambleOptions,
): Required<ScrambleOptions> {
  return {
    ...DEFAULTS,
    from: (el.getAttribute("from") as ScrambleOrigin | null) ?? DEFAULTS.from,
    duration: el.hasAttribute("duration")
      ? Number(el.getAttribute("duration"))
      : DEFAULTS.duration,
    cursor: el.getAttribute("cursor") ?? DEFAULTS.cursor,
    perturbation: el.hasAttribute("perturbation")
      ? Number(el.getAttribute("perturbation"))
      : DEFAULTS.perturbation,
    ignore: el.getAttribute("ignore") ?? setupDefaultIgnore,
    ...overrides,
  };
}

// --- DOM construction ---

function buildWordTree(text: string): {
  fragment: DocumentFragment;
  wordRoots: WordRoot[];
} {
  const fragment = document.createDocumentFragment();
  const wordRoots: WordRoot[] = [];
  let currentRoot: WordRoot | null = null;

  for (const character of [...text]) {
    if (character === " ") {
      currentRoot = null;
      fragment.appendChild(document.createTextNode(" "));
      continue;
    }

    if (!currentRoot) {
      const wordEl = document.createElement("span");
      wordEl.dataset.scrambleWord = "";
      const realEl = document.createElement("span");
      realEl.dataset.scrambleReal = "";
      wordEl.appendChild(realEl);
      fragment.appendChild(wordEl);
      currentRoot = { wordEl, realEl, characters: [] };
      wordRoots.push(currentRoot);
    }

    currentRoot.realEl.appendChild(document.createTextNode(character));
    currentRoot.characters.push(character);
  }

  return { fragment, wordRoots };
}

function mountOverlays(wordRoots: WordRoot[]): CharSlot[] {
  const slots: CharSlot[] = [];

  for (const root of wordRoots) {
    const overlayEl = document.createElement("span");
    overlayEl.dataset.scrambleOverlay = "";
    overlayEl.setAttribute("aria-hidden", "true");

    for (const character of root.characters) {
      const charEl = document.createElement("span");
      charEl.textContent = character;
      overlayEl.appendChild(charEl);
      slots.push({ charEl, targetChar: character });
    }

    root.realEl.style.visibility = "hidden";
    root.wordEl.appendChild(overlayEl);
    root.overlayEl = overlayEl;
  }

  return slots;
}

function unmountOverlays(wordRoots: WordRoot[]) {
  for (const root of wordRoots) {
    root.overlayEl?.remove();
    root.overlayEl = undefined;
    root.realEl.style.removeProperty("visibility");
  }
}

// --- Wave timing ---

function buildWaveContext(
  options: Required<ScrambleOptions>,
  slotCount: number,
): WaveContext {
  const settleRatio = options.settleDuration / options.duration;
  const settleSpacing = (1 - settleRatio) / slotCount;
  return {
    cursorChars: options.cursor,
    cursorLength: options.cursor.length,
    cursorZone: options.cursor.length * settleSpacing,
    settleSpacing,
    settleRatio,
    charset: options.chars,
    charsetLength: options.chars.length,
    perturbationScale:
      options.perturbation > 0 ? options.perturbation * settleRatio : 0,
  };
}

// Random per-cell start/end offsets keep the wave from reading as a
// perfect line.
function buildAnimationPlans(
  slots: CharSlot[],
  origin: ScrambleOrigin,
  ctx: WaveContext,
): AnimationPlan[] {
  const order = buildWaveOrder(slots.length, origin);
  return slots.map((slot, i) => {
    const startJitter =
      ctx.perturbationScale > 0
        ? (Math.random() - 0.5) * 2 * ctx.perturbationScale
        : 0;
    const endJitter =
      ctx.perturbationScale > 0
        ? (Math.random() - 0.5) * 2 * ctx.perturbationScale
        : 0;
    const startTime = order[i]! * ctx.settleSpacing + startJitter;
    return {
      slot,
      startTime,
      endTime: startTime + ctx.settleRatio + endJitter,
      scratchChar: ctx.charset[Math.floor(Math.random() * ctx.charsetLength)]!,
    };
  });
}

function buildWaveOrder(count: number, origin: ScrambleOrigin): Int32Array {
  const order = new Int32Array(count);

  if (origin === "random") {
    const indices = Array.from({ length: count }, (_, i) => i);
    for (let i = count - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [indices[i], indices[j]] = [indices[j]!, indices[i]!];
    }
    for (let i = 0; i < count; i++) order[indices[i]!] = i;
    return order;
  }

  const pivotIndex =
    origin === "right" ? count - 1 : origin === "center" ? (count - 1) / 2 : 0;
  const indices = Array.from({ length: count }, (_, i) => i);
  indices.sort((a, b) => Math.abs(a - pivotIndex) - Math.abs(b - pivotIndex));
  for (let i = 0; i < count; i++) order[indices[i]!] = i;
  return order;
}

// --- Frame rendering ---

function renderPlan(
  plan: AnimationPlan,
  easedTime: number,
  ctx: WaveContext,
  shouldRefresh: boolean,
) {
  const { charEl, targetChar } = plan.slot;

  if (easedTime >= plan.endTime || easedTime < plan.startTime) {
    setText(charEl, targetChar);
    setCursor(charEl, false);
    return;
  }

  const localTime = easedTime - plan.startTime;
  if (localTime < ctx.cursorZone) {
    const cursorIndex = Math.floor(localTime / ctx.settleSpacing);
    setText(charEl, ctx.cursorChars[ctx.cursorLength - 1 - cursorIndex] ?? "");
    setCursor(charEl, true);
    return;
  }

  if (shouldRefresh) {
    plan.scratchChar =
      ctx.charset[Math.floor(Math.random() * ctx.charsetLength)]!;
  }
  setText(charEl, plan.scratchChar);
  setCursor(charEl, false);
}

function setText(el: HTMLSpanElement, value: string) {
  if (el.firstChild?.nodeValue !== value) el.textContent = value;
}

function setCursor(el: HTMLSpanElement, isCursor: boolean) {
  if (isCursor) el.classList.add("is-cursor");
  else el.classList.remove("is-cursor");
}

// --- Cursor scale (one-time global probe) ---

const CSS_VALUE_PRECISION = 4;

// Below this, the cursor block already reads at letter height
const CURSOR_SCALE_THRESHOLD = 0.02;

let isCursorScaleProbed = false;
let cursorScalePromise: Promise<void> | null = null;

async function ensureCursorScale(referenceEl: HTMLElement): Promise<void> {
  if (isCursorScaleProbed) return;
  if (cursorScalePromise) return cursorScalePromise;

  cursorScalePromise = (async () => {
    try {
      await document.fonts.ready;
    } catch {
      // Best-effort – probe with whatever's available
    }
    const scale = computeCursorScale(
      buildFontShorthand(getComputedStyle(referenceEl)),
    );
    if (scale && Math.abs(scale - 1) > CURSOR_SCALE_THRESHOLD) {
      document.documentElement.style.setProperty(
        "--scramble-cursor-scale",
        scale.toFixed(CSS_VALUE_PRECISION),
      );
    }
    isCursorScaleProbed = true;
  })();

  return cursorScalePromise;
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

// --- Style injection ---

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

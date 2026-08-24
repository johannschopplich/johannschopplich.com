import { prefersReducedMotion } from "./_shared";

/** Drives the apparent pixel size. */
const COLUMNS = 40;
/** Fraction of each cell left empty. */
const CELL_GAP = 0.28;
const ACCENT_SHARE = 0.07;
const DEFAULT_TEXT = "404";

/** Time from a full field down to the decay floor. */
const DECAY_DURATION = 120_000;
/** Share of cells that survives, so the field never becomes an empty frame. */
const DECAY_FLOOR = 0.28;
/** Width of the crossfade band, in units of the alive fraction. */
const FADE_BAND = 0.04;
const DECAY_FRAME_INTERVAL = 100;

const CELL_FADE_DURATION = 400;
/** Stagger across the field, so the text reassembles rather than blinking on. */
const REBUILD_SPREAD = 600;
const REBUILD_TOTAL = CELL_FADE_DURATION + REBUILD_SPREAD;

interface Cell {
  /** Position, in device pixels relative to the canvas. */
  x: number;
  y: number;
  rebuildDelay: number;
  /** Stable 0..1 – the cell dies once the alive fraction drops below it. */
  lifetime: number;
  isAccent: boolean;
}

/**
 * Rasterizes text into a boolean mask `columns` cells wide by drawing it into
 * an offscreen canvas at exactly grid resolution – one pixel per cell.
 */
function rasterize(
  text: string,
  columns: number,
  font: string,
): { rows: number; mask: boolean[] } {
  const measuringContext = document.createElement("canvas").getContext("2d")!;
  measuringContext.font = font;
  const metrics = measuringContext.measureText(text);

  // Tight ink box, so the grid carries no dead margin.
  const inkWidth =
    metrics.actualBoundingBoxLeft + metrics.actualBoundingBoxRight;
  const inkHeight =
    metrics.actualBoundingBoxAscent + metrics.actualBoundingBoxDescent;
  const rows = Math.max(1, Math.round((columns * inkHeight) / inkWidth));

  const canvas = document.createElement("canvas");
  canvas.width = columns;
  canvas.height = rows;

  const context = canvas.getContext("2d", { willReadFrequently: true })!;
  context.font = font;
  context.fillStyle = "#000";
  context.setTransform(columns / inkWidth, 0, 0, rows / inkHeight, 0, 0);
  context.fillText(
    text,
    metrics.actualBoundingBoxLeft,
    metrics.actualBoundingBoxAscent,
  );

  const { data } = context.getImageData(0, 0, columns, rows);
  const mask: boolean[] = [];
  for (let index = 0; index < columns * rows; index++) {
    // Alpha of each RGBA quadruple; past half opaque counts as ink.
    mask.push(data[index * 4 + 3]! > 128);
  }

  return { rows, mask };
}

const easeOutCubic = (progress: number) => 1 - (1 - progress) ** 3;

const clamp01 = (value: number) => Math.min(1, Math.max(0, value));

/**
 * Returns a deterministic 0..1 for `seed`, so accents and lifetimes survive a
 * resize. The classic GLSL one-liner hash.
 */
function pseudoRandom(seed: number): number {
  const value = Math.sin(seed) * 43_758.545_312;
  return value - Math.floor(value);
}

export class PixelText extends HTMLElement {
  #text = DEFAULT_TEXT;
  #canvas = document.createElement("canvas");
  #context = this.#canvas.getContext("2d")!;
  #cells: Cell[] = [];
  #cellSize = 0;
  #builtForWidth = 0;

  #decayFrom = 0;
  /** Undefined until the first rebuild: the text starts out fully present. */
  #rebuildFrom?: number;
  #aliveFractionAtRestore = 1;
  #animationFrameId?: number;
  #decayTimerId?: ReturnType<typeof setTimeout>;

  #resizeObserver?: ResizeObserver;
  #themeObserver?: MutationObserver;
  #baseColor = "";
  #accentColor = "";
  #font = "";

  async connectedCallback() {
    this.#text = this.getAttribute("text")?.trim() || DEFAULT_TEXT;

    this.#canvas.style.display = "block";
    this.#canvas.style.width = "100%";

    // Drops the `<noscript>` fallback markup now that the canvas can take over.
    this.textContent = "";
    this.append(this.#canvas);
    this.setAttribute("aria-hidden", "true");

    this.#readStyles();
    this.#themeObserver = new MutationObserver(() => {
      this.#readStyles();
      this.#draw(performance.now());
    });
    this.#themeObserver.observe(document.documentElement, {
      attributes: true,
      attributeFilter: ["data-theme"],
    });

    // Canvas `measureText` never triggers a webfont download, and no DOM text
    // on the page uses weight 900 – without this explicit load the raster
    // silently falls back to Cooper Hewitt Book and the glyphs come out thin.
    await document.fonts.load(this.#font, this.#text);
    if (!this.isConnected) return;

    // Observing fires once by itself, which is the first build.
    this.#resizeObserver = new ResizeObserver(() => this.#build());
    this.#resizeObserver.observe(this);
  }

  disconnectedCallback() {
    this.#stopAnimating();
    this.#resizeObserver?.disconnect();
    this.#themeObserver?.disconnect();
  }

  /** Fades the lost pixels back in, then lets the decay start over. */
  restore() {
    const now = performance.now();
    this.#aliveFractionAtRestore = this.#aliveFractionAt(now);
    this.#rebuildFrom = now;
    this.#decayFrom = now + REBUILD_TOTAL;
    this.#startAnimating();
  }

  #aliveFractionAt(now: number): number {
    const decayProgress = clamp01((now - this.#decayFrom) / DECAY_DURATION);
    // Front-loaded, so the field is visibly crumbling within seconds and then
    // settles into the ruin. An even rate reads as nothing happening at all.
    return 1 - (1 - DECAY_FLOOR) * easeOutCubic(decayProgress);
  }

  #readStyles() {
    const styles = getComputedStyle(this);
    this.#baseColor = styles.color;
    this.#accentColor = styles.getPropertyValue("--un-color-secondary").trim();
    // Heavy, well above the weight the headings use. The raster size is
    // arbitrary – only its ratio to the measured ink box matters.
    this.#font = `900 200px ${styles.fontFamily}`;
  }

  #build() {
    const width = this.clientWidth;
    if (!width) return;

    // Setting the canvas height changes this element's own box, which the
    // ResizeObserver reports right back. Only width may trigger a rebuild.
    if (width === this.#builtForWidth) return;
    this.#builtForWidth = width;

    const { rows, mask } = rasterize(this.#text, COLUMNS, this.#font);

    const devicePixelRatio = Math.min(window.devicePixelRatio || 1, 2);
    this.#cellSize = (width * devicePixelRatio) / COLUMNS;
    this.#canvas.width = Math.round(width * devicePixelRatio);
    this.#canvas.height = Math.round(rows * this.#cellSize);
    this.#canvas.style.height = `${(rows * width) / COLUMNS}px`;

    this.#cells = [];
    for (let row = 0; row < rows; row++) {
      for (let column = 0; column < COLUMNS; column++) {
        if (!mask[row * COLUMNS + column]) continue;

        // Two independent draws: with one, every accent would sit below the
        // decay floor and never die, leaving the ruin three times as orange.
        const lifetime = pseudoRandom(column * 7919 + row * 104_729);
        const accentDraw = pseudoRandom(column * 31_337 + row * 15_485_863);

        this.#cells.push({
          x: column * this.#cellSize,
          y: row * this.#cellSize,
          rebuildDelay: (column / COLUMNS) * REBUILD_SPREAD,
          lifetime,
          isAccent: accentDraw < ACCENT_SHARE,
        });
      }
    }

    // A resize mid-decay must not silently reset the clock.
    if (!this.#decayFrom) this.#decayFrom = performance.now();
    this.#startAnimating();
  }

  #startAnimating() {
    this.#stopAnimating();

    if (prefersReducedMotion.matches) {
      this.#draw(performance.now());
      return;
    }

    const step = () => {
      const now = performance.now();
      this.#draw(now);

      const isRebuilding =
        this.#rebuildFrom !== undefined &&
        now - this.#rebuildFrom < REBUILD_TOTAL;

      if (isRebuilding) {
        // The fade-in needs every frame; the decay does not.
        this.#animationFrameId = requestAnimationFrame(step);
      } else if (now - this.#decayFrom < DECAY_DURATION) {
        this.#decayTimerId = setTimeout(step, DECAY_FRAME_INTERVAL);
      }
    };

    step();
  }

  #stopAnimating() {
    if (this.#animationFrameId) cancelAnimationFrame(this.#animationFrameId);
    if (this.#decayTimerId) clearTimeout(this.#decayTimerId);
    this.#animationFrameId = undefined;
    this.#decayTimerId = undefined;
  }

  #draw(now: number) {
    const context = this.#context;
    context.clearRect(0, 0, this.#canvas.width, this.#canvas.height);

    const isReducedMotion = prefersReducedMotion.matches;
    const aliveFraction = isReducedMotion ? 1 : this.#aliveFractionAt(now);

    const size = this.#cellSize * (1 - CELL_GAP);
    const inset = (this.#cellSize - size) / 2;

    for (const cell of this.#cells) {
      // Only the cells that were already gone fade back in. Animating the
      // survivors too would blank the whole field for a beat, so clicking
      // "rebuild" would read as breaking it rather than healing it.
      const isReturning =
        !isReducedMotion &&
        this.#rebuildFrom !== undefined &&
        cell.lifetime >= this.#aliveFractionAtRestore;

      const rebuildAlpha = isReturning
        ? clamp01(
            (now - this.#rebuildFrom! - cell.rebuildDelay) / CELL_FADE_DURATION,
          )
        : 1;

      const decayAlpha = clamp01((aliveFraction - cell.lifetime) / FADE_BAND);
      const alpha = rebuildAlpha * decayAlpha;
      if (alpha <= 0) continue;

      context.globalAlpha = alpha;
      context.fillStyle = cell.isAccent ? this.#accentColor : this.#baseColor;
      context.fillRect(cell.x + inset, cell.y + inset, size, size);
    }

    context.globalAlpha = 1;
  }
}

export function setup() {
  customElements.define("pixel-text", PixelText);
}

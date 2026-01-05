import type { Drauu, DrawingMode } from "drauu";
import { downloadFile, isBelow, startAlpine } from "../utils";

const DRAW_COLOR = "#fff";
const EXPORT_COLOR = "#000";

type KeyboardAction = (drauu: Drauu) => void;

const KEYBOARD_ACTIONS: Record<string, KeyboardAction> = {
  KeyL: (drauu) => (drauu.mode = "line"),
  KeyD: (drauu) => (drauu.mode = "draw"),
  KeyS: (drauu) => (drauu.mode = "stylus"),
  KeyR: (drauu) => (drauu.mode = "rectangle"),
  KeyE: (drauu) => (drauu.mode = "ellipse"),
  KeyC: (drauu) => drauu.clear(),
  Equal: (drauu) => (drauu.brush.size += 0.5),
  Minus: (drauu) => (drauu.brush.size -= 0.5),
};

export default async function () {
  if (isBelow("md")) return;

  window.Alpine.data("drauuControls", () => ({
    drauu: null as Drauu | null,
    mode: "stylus" as DrawingMode,
    brushSize: 3,

    async init() {
      const { createDrauu } = await import("drauu");
      this.drauu = createDrauu({
        el: "#drauu-canvas",
        brush: {
          mode: "stylus",
          color: DRAW_COLOR,
          size: this.brushSize,
        },
      });

      this.registerKeyboardShortcuts();
    },

    setMode(newMode: DrawingMode) {
      this.mode = newMode;
      if (this.drauu) {
        this.drauu.mode = newMode as Drauu["mode"];
      }
    },

    updateBrushSize() {
      if (this.drauu) {
        this.drauu.brush.size = this.brushSize;
      }
    },

    undo() {
      this.drauu?.undo();
    },

    redo() {
      this.drauu?.redo();
    },

    clear() {
      this.drauu?.clear();
    },

    download() {
      if (!this.drauu?.el) return;

      const clone = this.drauu.el.cloneNode(true) as SVGElement;

      for (const el of clone.querySelectorAll(`[fill="${DRAW_COLOR}"]`)) {
        el.setAttribute("fill", EXPORT_COLOR);
      }

      const rect = this.drauu.el.getBoundingClientRect();
      const width = Math.round(rect.width);
      const height = Math.round(rect.height);

      // Set proper SVG attributes for export
      clone.setAttribute("xmlns", "http://www.w3.org/2000/svg");
      clone.setAttribute("viewBox", `0 0 ${width} ${height}`);
      clone.setAttribute("width", String(width));
      clone.setAttribute("height", String(height));

      // Remove unnecessary attributes
      clone.removeAttribute("id");
      clone.removeAttribute("class");

      const data = clone.outerHTML;
      const filename = `johann-${new Date().toJSON().slice(0, 10)}.svg`;
      downloadFile(data, filename, "image/svg+xml");
    },

    registerKeyboardShortcuts() {
      window.addEventListener("keydown", (event) => {
        if (!this.drauu) return;

        if (event.code === "KeyZ" && (event.ctrlKey || event.metaKey)) {
          if (event.shiftKey) {
            this.drauu.redo();
          } else {
            this.drauu.undo();
          }
          return;
        }

        if (event.shiftKey || event.ctrlKey || event.metaKey || event.altKey)
          return;

        KEYBOARD_ACTIONS[event.code]?.(this.drauu as Drauu);
      });
    },
  }));

  await startAlpine();
}

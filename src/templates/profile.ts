import type { Brush, Drauu } from "drauu";
import { downloadFile, isBelow } from "../utils";

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

  const { createDrauu } = await import("drauu");
  const drauu = createDrauu({
    el: "#drauu-canvas",
    brush: {
      mode: "stylus",
      color: DRAW_COLOR,
      size: 3,
    },
  });

  registerKeyboardShortcuts(drauu);

  $("undo")?.addEventListener("click", () => drauu.undo());
  $("redo")?.addEventListener("click", () => drauu.redo());
  $("clear")?.addEventListener("click", () => drauu.clear());

  $("download")?.addEventListener("click", () => {
    if (!drauu.el) return;

    drauu.el.setAttribute("xmlns", "http://www.w3.org/2000/svg");

    // Change all white strokes to black for export
    const clone = drauu.el.cloneNode(true) as SVGElement;
    for (const el of clone.querySelectorAll(`[stroke="${DRAW_COLOR}"]`)) {
      el.setAttribute("stroke", EXPORT_COLOR);
    }

    const data = clone.outerHTML;
    const filename = `johann-${new Date().toJSON().slice(0, 10)}.svg`;
    downloadFile(data, filename, "image/svg+xml");
  });

  $("size")?.addEventListener("input", (event) => {
    drauu.brush.size = Number((event.target as HTMLSelectElement).value);
  });

  const modes = createModeConfigs();

  for (const { el, brush } of modes) {
    el?.addEventListener("click", () => {
      modes.forEach(({ el }) => el?.classList.remove("is-active"));
      el.classList.add("is-active");
      if (brush.arrowEnd !== undefined) {
        drauu.brush.arrowEnd = brush.arrowEnd;
      }
      if (brush.mode) {
        drauu.mode = brush.mode;
      }
    });
  }
}

function $(id: string) {
  return document.getElementById(id);
}

function createModeConfigs(): {
  el: HTMLElement | null;
  brush: Partial<Brush>;
}[] {
  return [
    { el: $("m-stylus"), brush: { mode: "stylus" } },
    { el: $("m-eraser"), brush: { mode: "eraseLine" } },
    { el: $("m-draw"), brush: { mode: "draw" } },
    { el: $("m-line"), brush: { mode: "line" } },
  ];
}

function registerKeyboardShortcuts(drauu: Drauu) {
  window.addEventListener("keydown", (event) => {
    if (event.code === "KeyZ" && (event.ctrlKey || event.metaKey)) {
      if (event.shiftKey) {
        drauu.redo();
      } else {
        drauu.undo();
      }
      return;
    }

    if (event.shiftKey || event.ctrlKey || event.metaKey || event.altKey)
      return;

    KEYBOARD_ACTIONS[event.code]?.(drauu);
  });
}

function _getPrimaryColor() {
  return getComputedStyle(document.documentElement).getPropertyValue(
    "--un-color-primary-400",
  );
}

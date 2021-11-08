import { useBreakpoints } from "../hooks";
import type { Drauu, Brush } from "drauu";

const $ = (id: string) => document.getElementById(id);

const registerKeyboardShortcuts = (drauu: Drauu) => {
  window.addEventListener("keydown", (evt) => {
    if (evt.code === "KeyZ" && (evt.ctrlKey || evt.metaKey)) {
      if (evt.shiftKey) {
        drauu.redo();
      } else {
        drauu.undo();
      }

      return;
    }

    if (evt.shiftKey || evt.ctrlKey || evt.metaKey || evt.altKey) return;

    const actions: Record<string, () => void> = {
      KeyL: () => (drauu.mode = "line"),
      KeyD: () => (drauu.mode = "draw"),
      KeyS: () => (drauu.mode = "stylus"),
      KeyR: () => (drauu.mode = "rectangle"),
      KeyE: () => (drauu.mode = "ellipse"),
      KeyC: () => drauu.clear(),
      Equal: () => (drauu.brush.size += 0.5),
      Minus: () => (drauu.brush.size -= 0.5),
    };

    actions[evt.code]?.();
  });
};

export default async () => {
  const { isBelow } = useBreakpoints();
  if (isBelow("md")) return;

  const { createDrauu } = await import("drauu");
  const drauu = createDrauu({
    el: ".drauu-canvas",
    brush: {
      mode: "stylus",
      color: "hsl(357, 43%, 63%)",
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
    const data = drauu.el.outerHTML || "";
    const blob = new Blob([data], { type: "image/svg+xml" });
    const el = window.document.createElement("a");
    el.href = window.URL.createObjectURL(blob);
    el.download = `johann-${new Date().toJSON().slice(0, 10)}.svg`;
    document.body.appendChild(el);
    el.click();
    document.body.removeChild(el);
  });

  $("size")?.addEventListener(
    "input",
    (evt) =>
      // @ts-expect-error: event target is an HTML element
      (drauu.brush.size = +evt.target.value)
  );

  const modes: { el: HTMLElement; brush: Partial<Brush> }[] = [
    { el: $("m-stylus")!, brush: { mode: "stylus" } },
    { el: $("m-draw")!, brush: { mode: "draw" } },
    { el: $("m-line")!, brush: { mode: "line" } },
  ];

  for (const { el, brush } of modes) {
    el?.addEventListener("click", () => {
      modes.forEach(({ el }) => el.classList.remove("is-active"));
      el.classList.add("is-active");
      Object.assign(drauu.brush, brush);
    });
  }
};
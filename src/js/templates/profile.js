import { useBreakpoints } from "../hooks";

/** @param {import("drauu").Drauu} drauu */
const registerKeyboardShortcuts = (drauu) => {
  window.addEventListener("keydown", (e) => {
    if (e.code === "KeyZ" && (e.ctrlKey || e.metaKey)) {
      if (e.shiftKey) {
        drauu.redo();
      } else {
        drauu.undo();
      }

      return;
    }

    if (e.shiftKey || e.ctrlKey || e.metaKey || e.altKey) return;

    if (e.code === "KeyL") drauu.mode = "line";
    else if (e.code === "KeyD") drauu.mode = "draw";
    else if (e.code === "KeyS") drauu.mode = "stylus";
    else if (e.code === "KeyR") drauu.mode = "rectangle";
    else if (e.code === "KeyE") drauu.mode = "ellipse";
    else if (e.code === "KeyC") drauu.clear();
    else if (e.code === "Equal") drauu.brush.size += 0.5;
    else if (e.code === "Minus") drauu.brush.size -= 0.5;
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
      color: "var(--color-accent)",
      size: 3,
    },
  });

  registerKeyboardShortcuts(drauu);

  document
    .getElementById("undo")
    ?.addEventListener("click", () => drauu.undo());
  document
    .getElementById("redo")
    ?.addEventListener("click", () => drauu.redo());
  document
    .getElementById("clear")
    ?.addEventListener("click", () => drauu.clear());

  document.getElementById("download")?.addEventListener("click", () => {
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

  /** @type {{el: HTMLElement, brush: Partial<import("drauu").Brush>}[]} */
  const modes = [
    {
      el: document.getElementById("m-stylus"),
      brush: { mode: "stylus", arrowEnd: false },
    },
    {
      el: document.getElementById("m-draw"),
      brush: { mode: "draw", arrowEnd: false },
    },
    {
      el: document.getElementById("m-line"),
      brush: { mode: "line", arrowEnd: false },
    },
  ];

  for (const { el, brush } of modes) {
    el?.addEventListener("click", () => {
      modes.forEach(({ el }) => el.classList.remove("is-active"));
      el.classList.add("is-active");
      Object.assign(drauu.brush, brush);
    });
  }
};

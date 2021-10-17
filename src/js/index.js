import Animere from "animere";
import "../scss/index.scss";

new Animere();

// Auto-load components
for (const { default: Component } of Object.values(
  import.meta.globEager("./components/*.js")
)) {
  new Component();
}

// Auto-load modules
for (const m of Object.values(import.meta.globEager("./modules/*.js"))) {
  m.install?.();
}

// Auto-load templates
const templates = Object.fromEntries(
  Object.entries(import.meta.glob("./templates/*.js")).map(([key, value]) => [
    key.slice(12, -3),
    value,
  ])
);

templates[document.body.dataset.template]?.().then((m) => m.default());

import "./styles/main.scss";

// Remove temporary CSS (to prevent FOUC) in development mode
if (import.meta.env.DEV) {
  for (const el of document.querySelectorAll(`[id*="vite-dev"]`)) {
    el.remove();
  }
}

// Auto-load components
for (const { default: Component } of Object.values(
  import.meta.globEager("./components/*.ts")
)) {
  new Component();
}

// Auto-load modules
for (const m of Object.values(import.meta.globEager("./modules/*.ts"))) {
  m.install?.();
}

// Auto-load templates
const templates = Object.fromEntries(
  Object.entries(import.meta.glob("./templates/*.ts")).map(([key, value]) => [
    key.slice(12, -3),
    value,
  ])
);

templates[document.body.dataset.template as string]?.().then((m) =>
  m.default()
);

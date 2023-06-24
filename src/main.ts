import "./styles/main.scss";
import "./fonts/CooperHewitt.css";
import "./fonts/IosevkaAile.css";

// Remove temporary stylesheet (to prevent FOUC) in development mode
if (import.meta.env.DEV) {
  for (const el of document.querySelectorAll(`[id*="vite-dev"]`)) {
    el.remove();
  }
}

// Auto-load modules
for (const m of Object.values(
  import.meta.glob<{ install?: () => void | Promise<void> }>("./modules/*.ts", {
    eager: true,
  })
)) {
  m.install?.();
}

// Auto-load templates
const templates = Object.fromEntries(
  Object.entries(
    import.meta.glob<{ default?: () => void | Promise<void> }>(
      "./templates/*.ts"
    )
  ).map(([key, value]) => [key.slice(12, -3), value])
);

const { template = "default" } = document.body.dataset;
templates[template]?.().then((m) => m.default?.());

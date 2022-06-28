/* eslint-disable @typescript-eslint/no-explicit-any */
import "./styles/main.scss";

export interface UserModule {
  install?: () => void | Promise<void>;
}

export interface UserTemplate {
  default?: () => void | Promise<void>;
}

// Remove temporary stylesheet (to prevent FOUC) in development mode
if (import.meta.env.DEV) {
  for (const el of document.querySelectorAll(`[id*="vite-dev"]`)) {
    el.remove();
  }
}

// Auto-load modules
for (const m of Object.values(
  import.meta.glob<true, string, UserModule>("./modules/*.ts", { eager: true })
)) {
  m.install?.();
}

// Auto-load templates
const templates = Object.fromEntries(
  Object.entries(
    import.meta.glob<true, string, () => Promise<UserTemplate>>(
      "./templates/*.ts"
    )
  ).map(([key, value]) => [key.slice(12, -3), value])
);

templates[document.body.dataset.template ?? ""]?.().then((m) => m.default?.());

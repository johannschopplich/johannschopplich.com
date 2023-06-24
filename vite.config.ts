import { resolve } from "node:path";
import { mkdirSync, writeFileSync } from "node:fs";
import { defineConfig } from "vite";
import FullReload from "vite-plugin-full-reload";
import { FontaineTransform } from "fontaine";
import type { Plugin as PostCssPlugin } from "postcss";

const currentDir = new URL(".", import.meta.url).pathname;

export default defineConfig(({ mode }) => ({
  root: "src",
  base: mode === "development" ? "/" : "/dist/",

  build: {
    outDir: resolve(currentDir, "public/dist"),
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: resolve(currentDir, "src/main.ts"),
    },
  },

  css: {
    postcss: {
      plugins: [postCssDevStyles()],
    },
  },

  plugins: [
    FullReload("site/{snippets,templates}/**/*"),
    FontaineTransform.vite({
      fallbacks: [
        "ui-sans-serif",
        "system-ui",
        "Segoe UI",
        "Roboto",
        "Helvetica Neue",
        "Arial",
        "Noto Sans",
      ],
      resolvePath: (id) => new URL(`src/fonts/${id}`, import.meta.url),
      overrideName: (name) => `${name} override`,
    }),
  ],
}));

/**
 * Prevent FOUC in development mode before Vite
 * injects the CSS into the page
 */
function postCssDevStyles(): PostCssPlugin {
  return {
    postcssPlugin: "postcss-vite-dev-css",

    OnceExit(root, { result }) {
      // @ts-expect-error: property unknown
      if (result.opts.env !== "production") {
        const outDir = resolve(currentDir, "public/assets/dev");
        mkdirSync(outDir, { recursive: true });
        writeFileSync(resolve(outDir, "index.css"), root.toResult().css);
      }
    },
  };
}

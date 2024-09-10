import { mkdirSync, writeFileSync } from "node:fs";
import { resolve } from "node:path";
import { defineConfig } from "vite";
import FullReload from "vite-plugin-full-reload";
import type { Plugin as PostCSSPlugin } from "postcss";

const currentDir = new URL(".", import.meta.url).pathname;

export default defineConfig(({ mode }) => {
  const isProd = mode === "production";

  return {
    root: "src",
    base: isProd ? "/dist/" : "/",

    build: {
      outDir: resolve(currentDir, "public/dist"),
      emptyOutDir: true,
      manifest: true,
      rollupOptions: {
        input: resolve(currentDir, "src/main.ts"),
      },
    },

    define: {
      __ENABLE_HASH_DECODING__: false,
    },

    css: {
      postcss: {
        ...(!isProd && { plugins: [exportDevStyles()] }),
      },
    },

    plugins: [FullReload("site/{snippets,templates}/**/*")],
  };
});

/**
 * Prevent FOUC in development mode before Vite
 * injects the CSS into the page
 */
function exportDevStyles(): PostCSSPlugin {
  return {
    postcssPlugin: "postcss-vite-dev-css",
    OnceExit(root) {
      const outDir = resolve(currentDir, "public/assets/dev");
      mkdirSync(outDir, { recursive: true });
      writeFileSync(resolve(outDir, "index.css"), root.toResult().css);
    },
  };
}

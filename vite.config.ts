import type { Plugin as PostCSSPlugin } from "postcss";
import * as fs from "node:fs";
import * as path from "node:path";
import postcssCustomMedia from "postcss-custom-media";
import postcssNesting from "postcss-nesting";
import { defineConfig } from "vite";
import FullReload from "vite-plugin-full-reload";
import { breakpoints } from "./src/breakpoints.ts";

const currentDir = import.meta.dirname;

export default defineConfig(({ mode }) => {
  const isProd = mode === "production";

  return {
    root: "src",
    base: isProd ? "/dist/" : "/",

    build: {
      outDir: path.resolve(currentDir, "public/dist"),
      copyPublicDir: false,
      emptyOutDir: true,
      manifest: true,
      rollupOptions: {
        input: path.resolve(currentDir, "src/main.ts"),
      },
    },

    define: {
      __UNLAZY_HASH_DECODING__: false,
      __UNLAZY_LOGGING__: false,
    },

    css: {
      postcss: {
        plugins: [
          prependCustomMedia(),
          postcssCustomMedia(),
          postcssNesting(),
          ...(!isProd ? [exportDevStyles()] : []),
        ],
      },
    },

    plugins: [FullReload("site/{snippets,templates}/**/*")],

    server: {
      cors: true,
      port: 5173,
      strictPort: true,
    },
  };
});

/**
 * Prepends the breakpoint custom media from `src/breakpoints.ts` to every
 * stylesheet, so CSS and UnoCSS read the same px widths.
 */
function prependCustomMedia(): PostCSSPlugin {
  const rules = Object.entries(breakpoints).flatMap(([name, width]) => [
    `@custom-media --${name} (min-width: ${width}px);`,
    // 0.1px under the min-width, like UnoCSS's `max-*` variants.
    `@custom-media --below-${name} (max-width: ${width - 0.1}px);`,
  ]);

  return {
    postcssPlugin: "postcss-prepend-custom-media",
    Once(root) {
      root.prepend(rules.join("\n"));
    },
  };
}

/**
 * Prevents FOUC in development mode before Vite injects the CSS into the page.
 */
function exportDevStyles(): PostCSSPlugin {
  return {
    postcssPlugin: "postcss-vite-dev-css",
    OnceExit(root) {
      const outDir = path.resolve(currentDir, "public/assets/dev");
      fs.mkdirSync(outDir, { recursive: true });
      fs.writeFileSync(path.resolve(outDir, "index.css"), root.toResult().css);
    },
  };
}

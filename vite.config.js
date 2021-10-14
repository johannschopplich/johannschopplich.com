/* eslint-env node */
import { defineConfig } from "vite";
import { resolve } from "path";

const root = "src/js";

export default defineConfig(({ mode }) => ({
  root,
  base: mode === "development" ? "/" : "/dist/",

  build: {
    outDir: resolve(__dirname, "public/dist"),
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: resolve(root, "index.js"),
    },
  },

  server: {
    cors: true,
    port: 3000,
    strictPort: true,
  },

  optimizeDeps: {
    include: ["animere", "medium-zoom"],
  },
}));

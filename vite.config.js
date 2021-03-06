/* eslint-env node */
import { defineConfig } from "vite";
import { resolve } from "path";
import liveReload from "vite-plugin-live-reload";

export default ({ mode }) =>
  defineConfig({
    root: "src/js",
    base: mode === "development" ? "/" : "/dist/",

    build: {
      outDir: resolve(__dirname, "public/dist"),
      emptyOutDir: true,
      manifest: true,
      rollupOptions: {
        input: "/index.js",
      },
    },

    plugins: [
      liveReload("site/(controllers|models|snippets|templates)/**/*.php"),
    ],

    server: {
      cors: true,
      port: 3000,
      strictPort: true,
    },

    optimizeDeps: {
      include: ["animere", "flickity", "medium-zoom"],
    },
  });

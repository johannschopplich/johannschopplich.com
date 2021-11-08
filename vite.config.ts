import { resolve } from "path";
import { defineConfig } from "vite";

export default defineConfig(({ mode }) => ({
  root: "src",
  base: mode === "development" ? "/" : "/dist/",

  build: {
    outDir: resolve(__dirname, "public/dist"),
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: [resolve(__dirname, "src/main.ts")],
    },
  },

  server: {
    fs: { strict: true },
    cors: true,
    port: 3000,
    strictPort: true,
  },

  optimizeDeps: {
    include: ["animere", "medium-zoom"],
  },
}));

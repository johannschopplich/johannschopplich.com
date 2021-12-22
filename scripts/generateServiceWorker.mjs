// @ts-check

import "dotenv/config";
import fg from "fast-glob";
import { readFile, writeFile } from "fs/promises";
import { transform } from "esbuild";
import { nanoid } from "nanoid";
import consola from "consola";

const srcPath = "src/service-worker.ts";
const distPath = "public/service-worker.js";

async function main() {
  if (process.env.VITE_SERVICE_WORKER !== "true") return;

  consola.start("building service worker...");

  const inputFiles = await fg("public/{assets,dist}/**/*.{css,js,woff2}");

  const assets = inputFiles.map((path) => path.replace(/^public/, ""));
  const bundle = `
    self.__PRECACHE_MANIFEST = [${assets.map((i) => `"${i}"`).join(",")}]
    const VERSION = "${nanoid()}"
    const KIRBY_PANEL_SLUG = "${process.env.KIRBY_PANEL_SLUG || "panel"}"
    const KIRBY_API_SLUG = "${process.env.KIRBY_API_SLUG || "api"}"
    ${await readFile(srcPath)}
  `;

  const { code } = await transform(bundle, { loader: "ts", minify: true });
  await writeFile(distPath, code);

  consola.success(`${assets.length} assets added to precache`);
}

main().catch((err) => consola.error(err));

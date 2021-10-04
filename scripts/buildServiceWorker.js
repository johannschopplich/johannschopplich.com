// @ts-check
/* eslint-env node */

require("dotenv").config();
const fg = require("fast-glob");
const { readFile, writeFile } = require("fs/promises");
const { transform } = require("esbuild");
const { nanoid } = require("nanoid");
const { green } = require("colorette");

const swSrcPath = "src/js/service-worker.js";
const swDistPath = "public/service-worker.js";

/**
 * Main entry point
 */
async function main() {
  if (process.env.VITE_SERVICE_WORKER !== "true") return;

  console.log(green("Building service worker..."));

  const inputFiles = fg.sync("public/{assets,dist}/**/*.{css,js,woff2}");

  const assets = inputFiles.map((path) => path.replace(/^public/, ""));
  const bundle = `
    self.__PRECACHE_MANIFEST = [${assets.map((i) => `"${i}"`).join(",")}]
    const VERSION = "${nanoid()}"
    const KIRBY_PANEL_SLUG = "${process.env.KIRBY_PANEL_SLUG || "panel"}"
    const KIRBY_API_SLUG = "${process.env.KIRBY_API_SLUG || "api"}"
    ${await readFile(swSrcPath)}
  `;

  const { code } = await transform(bundle, { minify: true });
  await writeFile(swDistPath, code);

  console.log(
    `${green("✓")} Added ${
      assets.length
    } additional service worker assets to precache.`
  );
}

main().catch(() => process.exit(1));

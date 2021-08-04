/* eslint-env node */
require("dotenv").config();
const fs = require("fs");
const fg = require("fast-glob");
const { nanoid } = require("nanoid");
const { transformSync } = require("esbuild");

const inputFiles = fg.sync("public/{assets,dist}/**/*.{css,js,woff2}");
const swSrcPath = "src/js/service-worker.js";
const swDistPath = "public/service-worker.js";

const assets = inputFiles.map((path) => path.replace(/^public/, ""));
const bundle = `
  self.__PRECACHE_MANIFEST = [${assets.map((i) => `'${i}'`).join(",")}]
  const VERSION = '${nanoid()}'
  const PANEL_SLUG = '${process.env.KIRBY_PANEL_SLUG ?? "panel"}'
  const API_SLUG = '${process.env.KIRBY_API_SLUG ?? "api"}'
  ${fs.readFileSync(swSrcPath)}
`;

const { code } = transformSync(bundle, { minify: true });
fs.writeFileSync(swDistPath, code);
console.log(
  "\x1b[32m%s\x1b[0m",
  `Created service worker with ${assets.length} additional assets to precache.`
);

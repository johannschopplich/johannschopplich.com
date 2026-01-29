import type { BlockFile, ContentBlock } from "./types.ts";
import * as fsp from "node:fs/promises";
import * as path from "node:path";
import process from "node:process";
import consola from "consola";
import { languages, templates } from "./constants.ts";
import { contentDir, dataDir } from "./utils.ts";

consola.start("Starting content block injection...");

const files = await findBlockFiles();

if (files.length === 0) {
  consola.info("No block files found in data directory.");
  process.exit(0);
}

consola.info(`Found ${files.length} block file(s) to inject`);

for (const file of files) {
  await injectBlocks(file);
}

consola.success(`Successfully injected ${files.length} file(s)`);

async function findBlockFiles(): Promise<BlockFile[]> {
  const files: BlockFile[] = [];

  try {
    const templatePart =
      templates.length > 1 ? `{${templates.join(",")}}` : templates[0];
    const langPart = `{${languages.join(",")}}`;
    const pattern = `**/${templatePart}.${langPart}.json`;

    const entries = await Array.fromAsync(
      fsp.glob(pattern, {
        cwd: dataDir,
        withFileTypes: true,
      }),
    );

    for (const entry of entries) {
      const fullPath = path.join(entry.parentPath, entry.name);
      const relativePath = path.relative(dataDir, entry.parentPath);

      // Parse template and language from filename
      const match = entry.name.match(
        new RegExp(`^(.+)\\.(${languages.join("|")})\\.json$`),
      );
      if (!match) continue;

      files.push({
        path: fullPath,
        folder: relativePath,
        template: match[1],
        lang: match[2],
      });
    }
  } catch (error) {
    throw new Error(
      `Failed to read data directory: ${(error as Error).message}`,
    );
  }

  return files;
}

async function injectBlocks({
  path: jsonPath,
  folder,
  lang,
  template,
}: {
  path: string;
  folder: string;
  lang: string;
  template: string;
}): Promise<void> {
  // Construct the original content file path
  const txtPath = path.join(contentDir, folder, `${template}.${lang}.txt`);

  let originalContent: string;
  let blocks: ContentBlock[];

  try {
    originalContent = await fsp.readFile(txtPath, "utf-8");
  } catch (error) {
    throw new Error(
      `Failed to read original file ${txtPath}: ${(error as Error).message}`,
    );
  }

  try {
    const jsonContent = await fsp.readFile(jsonPath, "utf-8");
    blocks = JSON.parse(jsonContent) as ContentBlock[];
  } catch (error) {
    throw new Error(
      `Failed to read/parse JSON ${jsonPath}: ${(error as Error).message}`,
    );
  }

  // Minify the JSON (no spaces)
  const minified = JSON.stringify(blocks);

  // Replace the Text field line
  const textFieldRegex = /^Text: \[.*\]$/m;

  if (!textFieldRegex.test(originalContent)) {
    throw new Error(`No Text field found in ${txtPath}`);
  }

  const updatedContent = originalContent.replace(
    textFieldRegex,
    `Text: ${minified}`,
  );

  try {
    await fsp.writeFile(txtPath, updatedContent, "utf-8");
    consola.success(`Injected: ${folder}/${template}.${lang}.txt`);
  } catch (error) {
    throw new Error(`Failed to write ${txtPath}: ${(error as Error).message}`);
  }
}

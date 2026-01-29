import type { ContentBlock, ProjectFile } from "./types.ts";
import * as fsp from "node:fs/promises";
import * as path from "node:path";
import process from "node:process";
import consola from "consola";
import { languages, templates } from "./constants.ts";
import { contentDir, dataDir, projectRoot } from "./utils.ts";

consola.start("Starting content block extraction...");

const files = await findTemplateFiles();

if (files.length === 0) {
  consola.info("No template files found.");
  process.exit(0);
}

consola.info(`Found ${files.length} template file(s) to process`);

for (const file of files) {
  const blocks = await extractBlocks(file.path);
  await saveBlocks({ ...file, blocks });
}

consola.success(
  `Successfully extracted ${files.length} file(s) to ${path.relative(projectRoot, dataDir)}`,
);

async function findTemplateFiles(): Promise<ProjectFile[]> {
  const files: ProjectFile[] = [];

  try {
    const templatePart =
      templates.length > 1 ? `{${templates.join(",")}}` : templates[0];
    const langPart = `{${languages.join(",")}}`;
    const pattern = `**/${templatePart}.${langPart}.txt`;

    const entries = await Array.fromAsync(
      fsp.glob(pattern, {
        cwd: contentDir,
        withFileTypes: true,
      }),
    );

    for (const entry of entries) {
      const fullPath = path.join(entry.parentPath, entry.name);
      const relativePath = path.relative(contentDir, entry.parentPath);

      // Parse template and language from filename
      const match = entry.name.match(
        new RegExp(`^(.+)\\.(${languages.join("|")})\\.txt$`),
      );
      if (!match) continue;

      files.push({
        path: fullPath,
        folder: relativePath,
        template: match[1]!,
        lang: match[2]!,
      });
    }
  } catch (error) {
    throw new Error(
      `Failed to read content directory: ${(error as Error).message}`,
    );
  }

  return files;
}

async function extractBlocks(filePath: string): Promise<ContentBlock[]> {
  let content: string;

  try {
    content = await fsp.readFile(filePath, "utf-8");
  } catch (error) {
    throw new Error(
      `Failed to read file ${filePath}: ${(error as Error).message}`,
    );
  }

  // Match the Text field with regex
  const textFieldRegex = /^Text: (\[.*\])$/m;
  const match = content.match(textFieldRegex);

  if (!match) {
    throw new Error(`No Text field found in ${filePath}`);
  }

  const jsonString = match[1]!;

  try {
    return JSON.parse(jsonString) as ContentBlock[];
  } catch (error) {
    throw new Error(
      `Failed to parse JSON in ${filePath}: ${(error as Error).message}`,
    );
  }
}

async function saveBlocks({
  folder,
  lang,
  template,
  blocks,
}: {
  folder: string;
  lang: string;
  template: string;
  blocks: ContentBlock[];
}): Promise<void> {
  const outputPath = path.join(dataDir, folder, `${template}.${lang}.json`);

  try {
    // Create directory if it doesn't exist
    await fsp.mkdir(path.join(dataDir, folder), { recursive: true });

    // Write pretty-printed JSON
    await fsp.writeFile(
      outputPath,
      `${JSON.stringify(blocks, null, 2)}\n`,
      "utf-8",
    );

    consola.success(`Extracted: ${folder}/${template}.${lang}.json`);
  } catch (error) {
    throw new Error(
      `Failed to write ${outputPath}: ${(error as Error).message}`,
    );
  }
}

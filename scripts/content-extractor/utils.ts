import * as path from "node:path";
import { fileURLToPath } from "node:url";

export const currentDir = path.dirname(fileURLToPath(import.meta.url));
export const projectRoot = path.join(currentDir, "..", "..");
export const contentDir = path.join(projectRoot, "storage", "content");
export const dataDir = path.join(currentDir, "data");

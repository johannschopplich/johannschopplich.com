import type { Preset } from "@unocss/core";
import { rules } from "./rules";
import { shortcuts } from "./shortcuts";
import { theme } from "./theme";
import { variants } from "./variants";

/**
 * DueCSS Preset
 *
 * @example
 * // unocss.config.ts
 * import { presetUno } from 'unocss'
 * import { presetDue } from 'duecss'
 *
 * export default defineConfig({
 *   presets: [
 *     presetUno(),
 *     presetDue()
 *   ]
 * })
 */
export function presetDue(): Preset {
  return {
    name: "unocss-preset-due",
    theme,
    rules,
    shortcuts,
    variants,
  };
}

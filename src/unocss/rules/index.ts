import type { Rule } from "@unocss/core";
import type { Theme } from "@unocss/preset-mini";
import { animated } from "./animated";
import { hyphenate } from "./hyphenate";
import { unselectable } from "./unselectable";
import { varColor } from "./var-color";

export const rules: Rule<Theme>[] = [
  animated,
  hyphenate,
  unselectable,
  varColor,
].flat(1);

import type { Rule } from "@unocss/core";
import type { Theme } from "@unocss/preset-wind4";
import { animated } from "./animated";
import { hyphenate } from "./hyphenate";
import { varColor } from "./var-color";

export const rules: Rule<Theme>[] = [animated, hyphenate, varColor].flat(1);

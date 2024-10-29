import type { Rule } from "@unocss/core";
import type { Theme } from "@unocss/preset-mini";
import { parseColor } from "@unocss/preset-mini/utils";

export const varColor: Rule<Theme>[] = [
  [
    /^var-color-(.+)$/,
    ([, body], { theme }) => {
      const parsed = parseColor(body, theme);
      if (!parsed) return;

      return {
        [`--un-color-${body}`]: parsed.color,
      };
    },
  ],
];

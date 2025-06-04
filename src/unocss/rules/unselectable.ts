import type { Rule } from "@unocss/core";
import type { Theme } from "@unocss/preset-wind3";

export const unselectable: Rule<Theme>[] = [
  [
    "unselectable",
    {
      "-webkit-touch-callout": "none",
      "-webkit-user-select": "none",
      "user-select": "none",
    },
  ],
];

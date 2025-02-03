import type { Rule } from "@unocss/core";
import type { Theme } from "@unocss/preset-mini";

export const hyphenate: Rule<Theme>[] = [
  [
    "hyphenate",
    {
      "overflow-wrap": "break-word",

      "-webkit-hyphens": "auto",
      "-webkit-hyphenate-limit-before": "4",
      "-webkit-hyphenate-limit-after": "4",
      "-webkit-hyphenate-limit-chars": "8 4 4",
      "-webkit-hyphenate-limit-lines": "2",
      "-webkit-hyphenate-limit-last": "always",
      "-webkit-hyphenate-limit-zone": "10%",

      hyphens: "auto",
      "hyphenate-limit-chars": "8 4 4",
      "hyphenate-limit-lines": "2",
      "hyphenate-limit-last": "always",
      "hyphenate-limit-zone": "10%",
    },
  ],
];

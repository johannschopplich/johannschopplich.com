import type { Theme } from "@unocss/preset-wind";
import type { Variant } from "unocss";
import { variantMatcher } from "@unocss/preset-mini/utils";

export const variants: Variant<Theme>[] = [
  variantMatcher("du-light", (input) => ({
    prefix: `:root[data-theme="light"] $$ ${input.prefix}`,
  })),
  variantMatcher("du-dark", (input) => ({
    prefix: `:root[data-theme="dark"] $$ ${input.prefix}`,
  })),
];

import { defineConfig, presetUno, presetIcons } from "unocss";
import { presetDue } from "duecss";

export default defineConfig({
  theme: {
    boxShadow: {
      frame: "var(--du-shadow-frame)",
    },
  },
  shortcuts: {
    content: "px-lg md:px-3xl",
    "content-breakout": "-mx-lg md:mx-0",
    "action-button":
      "p-2 -m-2 text-size-xs font-heading leading-heading font-500 text-center uppercase tracking-[0.125ch]",
    tag: "px-2 py-1 border-1 border-theme-base text-accent text-xs font-heading font-500 leading-heading rounded-full whitespace-nowrap",
  },
  safelist: ["w-full", "aspect-ratio-16/9"],
  presets: [
    presetUno(),
    presetIcons({
      extraProperties: {
        display: "inline-block",
        height: "1.25em",
        width: "1.25em",
        "vertical-align": "text-bottom",
      },
    }),
    presetDue(),
  ],
});

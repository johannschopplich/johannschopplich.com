import type { Theme } from "@unocss/preset-wind3";
import type { CSSObject, Rule } from "unocss";
import { handler } from "@unocss/preset-mini/utils";
import animatedJSON from "./animated.json";

export const durations = {
  faster: 0.5,
  fast: 0.8,
  slow: 2,
  slower: 3,
} as const;

export const animated: Rule<Theme>[] = [
  [
    "animated",
    {
      "--un-animated-duration": "1s",
      "animation-duration": "var(--un-animated-duration)",
      "animation-fill-mode": "both",
    },
  ],
  [
    new RegExp(`^animated-(${Object.keys(animatedJSON).join("|")})$`),
    ([, name]) => {
      const { animationName, css, keyframes } = getAnimated()[name];

      return [`@keyframes ${animationName} { ${keyframes} }`, css];
    },
    {
      autocomplete: [`animated-(${Object.keys(animatedJSON).join("|")})`],
    },
  ],
  [
    /^animated-(infinite|(repeat-(infinite|(\d+(\.\d+)?))))$/,
    ([, , , repeat]) => {
      const isInfinite = !repeat || repeat === "infinite";

      return {
        "animation-iteration-count": isInfinite ? "infinite" : repeat,
      };
    },
    {
      autocomplete: [
        "animated-infinite",
        "animated-repeat-infinite",
        "animated-repeat-<num>",
      ],
    },
  ],
  [
    /^animated-delay-(none|(\d+(\.\d+)?(m?s)?))$/,
    ([, d]) => ({
      "animation-delay": d === "none" ? "0ms" : handler.bracket.cssvar.time(d),
    }),
    {
      autocomplete: ["animated-delay-none", "animated-delay-$duration"],
    },
  ],
  [
    /^animated-(((fast|slow)(?:er)?)|duration-(none|(\d+(\.\d+)?(m?s)?)))/,
    ([_, , shortcut, , v]) => {
      if (shortcut) {
        return {
          "animation-duration": `calc(var(--un-animated-duration) * ${durations[shortcut as keyof typeof durations]});`,
        };
      }

      return {
        "animation-duration":
          v === "none" ? "0ms" : handler.bracket.cssvar.time(v),
      };
    },
    {
      autocomplete: [
        `animated-(${Object.keys(durations).join("|")})`,
        "animated-duration-none",
        "animated-duration-$duration",
      ],
    },
  ],
];

function getAnimated() {
  return animatedJSON as unknown as Record<
    string,
    {
      animationName: string;
      css: CSSObject;
      keyframes: string;
    }
  >;
}

import { defineConfig, presetUno } from "unocss";
import presetIcons from "@unocss/preset-icons";
import { presetDue } from "duecss";

export default defineConfig({
  rules: [
    [
      "framed",
      {
        "box-shadow":
          "3px 3px 0 var(--du-color-background), 4px 4px 0 currentColor",
      },
    ],
  ],
  presets: [presetUno(), presetIcons(), presetDue()],
});

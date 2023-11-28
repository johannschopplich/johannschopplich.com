import antfu from "@antfu/eslint-config";

export default await antfu(
  {
    stylistic: false,
    yaml: false,
    ignores: ["tsconfig.json", "**/plugins/*/index.js"],
  },
  {
    rules: {
      "node/prefer-global/buffer": "off",
      "node/prefer-global/process": "off",
    },
  },
);

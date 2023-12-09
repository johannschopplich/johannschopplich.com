import antfu from "@antfu/eslint-config";

export default await antfu({
  stylistic: false,
  yaml: false,
  ignores: ["tsconfig.json", "**/plugins/*/index.js", "**/vendor/**"],
});

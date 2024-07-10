import antfu from "@antfu/eslint-config";

export default await antfu({
  stylistic: false,
  yaml: false,
  ignores: ["**/site/plugins/*/index.js", "**/vendor/**"],
});

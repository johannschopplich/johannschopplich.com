// @ts-check
import antfu from "@antfu/eslint-config";

export default antfu({
  stylistic: false,
  ignores: ["**/site/plugins/*/index.js", "**/vendor/**"],
});

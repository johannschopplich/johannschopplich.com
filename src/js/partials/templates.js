const templateModules = import.meta.glob("../templates/*.js");
const currentTemplate = `../templates/${document.body.dataset.template}.js`;

(async () => {
  if (!(currentTemplate in templateModules)) return;

  const { default: Component } = await templateModules[currentTemplate]();
  await Component();
})();

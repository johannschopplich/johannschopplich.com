(async () => {
  const modules = Object.fromEntries(
    Object.entries(import.meta.glob("../templates/*.js")).map(([key, value]) => [
      key.slice(13, -3),
      value,
    ])
  );

  const template = modules[document.body.dataset.template];

  if (template) {
    const { default: Component } = await template();
    await Component();
  }
})();

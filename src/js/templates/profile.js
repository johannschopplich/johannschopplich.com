export default async () => {
  const { createDrauu } = await import("drauu");

  createDrauu({
    el: ".drauu",
    brush: {
      mode: "stylus",
      color: "var(--color-accent)",
      size: 2,
    },
  });
};

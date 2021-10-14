export default async () => {
  const { default: Splide } = await import("@splidejs/splide");
  const splide = new Splide(".carousel.is-photography", {
    gap: "var(--space-xs)",
    autoWidth: true,
    focus: "center",
    lazyLoad: "nearby",
    arrowPath:
      "m23.3 3.3-2.4 2.4 12.7 12.6H0v3.4h33.6L20.9 34.3l2.4 2.4L40 20z",
    pagination: false,
  });

  splide.mount();
};

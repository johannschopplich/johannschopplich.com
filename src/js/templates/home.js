export default async () => {
  const { default: Splide } = await import("@splidejs/splide");
  const splide = new Splide(".carousel.is-photography", {
    gap: "var(--space-s)",
    autoWidth: true,
    focus: "center",
    lazyLoad: "nearby",
    pagination: false,
  });

  splide.mount();
};

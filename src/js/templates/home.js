export default async () => {
  const { default: Flickity } = await import("flickity");
  new Flickity(".carousel.is-photography", {
    contain: true,
    cellSelector: ".carousel-cell",
    cellAlign: "center",
    setGallerySize: false,
    lazyLoad: 3,
    autoPlay: false,
    pauseAutoPlayOnHover: true,
    pageDots: false,
  });
};

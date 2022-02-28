export const install = () => {
  if (matchMedia("(hover: none)").matches) return;

  const slider = document.querySelector<HTMLElement>(".slider");
  if (!slider) return;

  const SCROLL_SPEED = 1;
  let isDown = false;
  let startX: number;
  let scrollLeft: number;

  const disableSnap = () => {
    isDown = true;
    slider.style.setProperty("--un-scroll-snap-strictness", "none");
    slider.style.setProperty("--cursor", "grabbing");
  };

  const enableSnap = () => {
    isDown = false;
    slider.style.setProperty("--un-scroll-snap-strictness", "mandatory");
    slider.style.setProperty("--cursor", "grab");
  };

  slider.addEventListener("mousedown", (e: MouseEvent) => {
    const target = e.target as HTMLElement;
    if (target.closest("[data-slider-ignore]")) return;

    disableSnap();
    startX = e.pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
  });

  slider.addEventListener("mouseleave", enableSnap);
  slider.addEventListener("mouseup", enableSnap);

  slider.addEventListener("mousemove", (e: MouseEvent) => {
    if (!isDown) return;
    e.preventDefault();

    const x = e.pageX - slider.offsetLeft;
    const walk = (x - startX) * SCROLL_SPEED;
    slider.scrollLeft = scrollLeft - walk;
  });
};

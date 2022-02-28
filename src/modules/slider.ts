export const install = () => {
  if (matchMedia("(hover: none)").matches) return;

  const slider = document.querySelector<HTMLElement>(".slider");
  if (!slider) return;

  const SCROLL_SPEED = 1;
  let isDown = false;
  let startX: number;
  let scrollLeft: number;

  const disableScroll = () => {
    isDown = true;
    slider.style.setProperty("--un-scroll-snap-strictness", "none");
    slider.style.setProperty("--cursor", "grabbing");
  };

  const enableScroll = () => {
    isDown = false;
    slider.style.setProperty("--un-scroll-snap-strictness", "mandatory");
    slider.style.setProperty("--cursor", "grab");
  };

  slider.addEventListener("mousedown", (e: MouseEvent) => {
    const target = e.target as HTMLElement;
    if (target.closest(".slider-grap-none")) return;

    disableScroll();
    startX = e.pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
  });

  slider.addEventListener("mouseleave", enableScroll);
  slider.addEventListener("mouseup", enableScroll);

  slider.addEventListener("mousemove", (e: MouseEvent) => {
    if (!isDown) return;
    e.preventDefault();

    const x = e.pageX - slider.offsetLeft;
    const walk = (x - startX) * SCROLL_SPEED;
    slider.scrollLeft = scrollLeft - walk;
  });
};

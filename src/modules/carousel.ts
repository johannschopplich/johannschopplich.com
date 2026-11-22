// Touch input scrolls the carousel natively; only a mouse needs Blossom's
// drag engine, so touch devices never load it.
const hasFinePointer = matchMedia("(hover: hover) and (pointer: fine)").matches;

export async function install() {
  const scrollers = [
    ...document.querySelectorAll<HTMLElement>("[data-carousel-scroller]"),
  ];
  if (scrollers.length === 0) return;

  for (const scroller of scrollers) {
    syncCounter(scroller);
  }

  if (!hasFinePointer) return;

  const { Blossom } = await import("@blossom-carousel/core");
  for (const scroller of scrollers) {
    Blossom(scroller).init();
  }
}

function syncCounter(scroller: HTMLElement) {
  const counterCurrent = scroller
    .closest("[data-carousel]")
    ?.querySelector<HTMLElement>("[data-carousel-current]");
  if (!counterCurrent) return;

  const slides = [
    ...scroller.querySelectorAll<HTMLElement>(':scope > [role="group"]'),
  ];
  let pendingFrame = 0;

  const updateCounter = () => {
    pendingFrame = 0;
    const maxScrollLeft = scroller.scrollWidth - scroller.clientWidth;
    // The last slide never reaches the center, so the scroll end selects it.
    const index =
      scroller.scrollLeft >= maxScrollLeft - 1
        ? slides.length - 1
        : findCenteredSlideIndex(scroller, slides);
    counterCurrent.textContent = String(index + 1);
  };

  scroller.addEventListener(
    "scroll",
    () => {
      pendingFrame ||= requestAnimationFrame(updateCounter);
    },
    { passive: true },
  );
}

function findCenteredSlideIndex(scroller: HTMLElement, slides: HTMLElement[]) {
  const center = scroller.scrollLeft + scroller.clientWidth / 2;
  let closestIndex = 0;
  let closestDistance = Infinity;

  for (const [index, slide] of slides.entries()) {
    const distance = Math.abs(
      slide.offsetLeft + slide.offsetWidth / 2 - center,
    );
    if (distance < closestDistance) {
      closestIndex = index;
      closestDistance = distance;
    }
  }

  return closestIndex;
}

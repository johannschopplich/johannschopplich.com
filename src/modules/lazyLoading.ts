export function install() {
  const elements = [
    ...document.querySelectorAll<HTMLImageElement>('img[loading="lazy"]'),
  ];

  if (elements.length === 0) return;

  for (const image of elements) {
    // Calculate the image's `sizes` attribute if `data-sizes="auto"` is set
    handleAutoSizes(image);

    // Bail if the image doesn't contain a blurry placeholder
    if (!image.dataset.srcset) continue;

    if (image.complete) {
      // Load the image if it's already in the viewport
      loadImage(image);
    } else {
      // Otherwise, load the image when it enters the viewport
      image.addEventListener("load", () => loadImage(image), { once: true });
    }
  }
}

function handleAutoSizes(element: HTMLImageElement) {
  const { sizes } = element.dataset;
  if (sizes === "auto") {
    element.sizes = `${element.offsetWidth}px`;
  }
}

function loadImage(element: HTMLImageElement) {
  const imageLoader = new Image();
  imageLoader.srcset = element.dataset.srcset!;
  imageLoader.sizes = element.sizes;

  imageLoader.addEventListener("load", () => {
    element.srcset = imageLoader.srcset;
    element.removeAttribute("data-srcset");
  });
}

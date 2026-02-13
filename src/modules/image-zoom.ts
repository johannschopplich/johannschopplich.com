import { isBelow } from "../utils";

export function install() {
  const images = document.querySelectorAll<HTMLImageElement>("[data-zoomable]");
  if (isBelow("md") || images.length === 0) return;

  const overlay = document.createElement("div");
  overlay.className = "image-zoom";
  overlay.role = "dialog";
  overlay.ariaModal = "true";
  overlay.tabIndex = -1;
  overlay.hidden = true;

  const zoomedImg = document.createElement("img");
  overlay.append(zoomedImg);
  document.body.append(overlay);

  let activeImage: HTMLImageElement | null = null;

  /** Runs `update` inside a view transition when supported. */
  function transition(update: () => void): Promise<void> {
    if (!document.startViewTransition) {
      update();
      return Promise.resolve();
    }
    return document.startViewTransition(update).finished;
  }

  async function openZoom(image: HTMLImageElement) {
    activeImage = image;
    overlay.ariaLabel = image.alt || "Zoomed image";

    // Start with cached source to avoid flicker
    zoomedImg.removeAttribute("srcset");
    zoomedImg.removeAttribute("sizes");
    zoomedImg.src = image.currentSrc || image.src;
    zoomedImg.alt = image.alt;

    // Pre-compute fitted size for a stable view-transition snapshot
    const rem = Number.parseFloat(
      getComputedStyle(document.documentElement).fontSize,
    );
    const padding = 4 * rem; // 2rem per side × 2 sides
    const vw = document.documentElement.clientWidth - padding;
    const vh = document.documentElement.clientHeight - padding;
    const nw = Number(image.getAttribute("width")) || image.naturalWidth;
    const nh = Number(image.getAttribute("height")) || image.naturalHeight;
    const scale = Math.min(vw / nw, vh / nh, 1);
    zoomedImg.style.width = `${Math.round(nw * scale)}px`;
    zoomedImg.style.height = `${Math.round(nh * scale)}px`;

    image.style.viewTransitionName = "zoom-image";

    await transition(() => {
      overlay.hidden = false;
      // Hand transition name to zoomed image
      image.style.viewTransitionName = "";
      zoomedImg.style.viewTransitionName = "zoom-image";
    });

    overlay.focus();
    // Upgrade to full-resolution srcset
    const srcset = image.dataset.srcset || image.srcset;
    if (srcset) {
      zoomedImg.srcset = srcset;
      zoomedImg.sizes = "100vw";
    }

    window.addEventListener("scroll", onScroll, { once: true, passive: true });
  }

  async function closeZoom(morphBack = true) {
    if (!activeImage) return;

    const image = activeImage;
    activeImage = null;
    window.removeEventListener("scroll", onScroll);

    // Downgrade to cached source for clean transition
    zoomedImg.removeAttribute("srcset");
    zoomedImg.removeAttribute("sizes");

    await transition(() => {
      zoomedImg.style.viewTransitionName = "";
      if (morphBack) {
        image.style.viewTransitionName = "zoom-image";
      }
      overlay.hidden = true;
      zoomedImg.style.width = "";
      zoomedImg.style.height = "";
    });

    image.style.viewTransitionName = "";
    image.focus({ preventScroll: true });
  }

  function onScroll() {
    closeZoom(false);
  }

  for (const image of images) {
    image.tabIndex = 0;
    image.classList.add("cursor-zoom-in");
    image.addEventListener("click", () => openZoom(image));
    image.addEventListener("keydown", (e) => {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        openZoom(image);
      }
    });
  }

  overlay.addEventListener("click", () => closeZoom());

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && activeImage) closeZoom();
  });
}

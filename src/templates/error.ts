import type { PixelText } from "../components/pixel-text";
import {
  ANIMATION_DELAY_MS,
  prefersReducedMotion,
} from "../components/_shared";
import { setup } from "../components/pixel-text";

export default function () {
  setup();

  const pixelText = document.querySelector<PixelText>("pixel-text");
  const restoreControl = document.querySelector<HTMLElement>(
    "[data-pixel-restore]",
  );
  if (!pixelText || !restoreControl || prefersReducedMotion.matches) return;

  // The 404 is the page's whole subject, so it holds twice the usual beat.
  pixelText.decayDelayMs = ANIMATION_DELAY_MS * 2;

  restoreControl.hidden = false;
  restoreControl.addEventListener("click", () => pixelText.restore());
}

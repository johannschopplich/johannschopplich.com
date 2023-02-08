import { ScrollSnapPlugin } from "./plugin";
import type { ScrollSnapSlider } from ".";

export class ScrollSnapDraggable extends ScrollSnapPlugin {
  private slider: ScrollSnapSlider | undefined;

  /**
   * Last drag event position
   */
  private lastX: number | undefined;

  /**
   * Where the dragging started
   */
  private startX: number | undefined;

  /**
   * Timeout ID for a smooth drag release
   */
  private disableTimeout: number | undefined;

  constructor(
    /**
     * If this is `undefined`: The next/previous slide will not be reached
     * unless you drag for more than half the slider's width.
     *
     * If this is a number: Dragging any slide for more than this distance
     * in pixels will slide to the next slide in the desired direction.
     */
    public quickSwipeDistance: number | undefined = 50
  ) {
    super();

    this.mouseMove = this.mouseMove.bind(this);
    this.startDragging = this.startDragging.bind(this);
    this.stopDragging = this.stopDragging.bind(this);
  }

  enable(slider: ScrollSnapSlider) {
    this.slider = slider;
    this.slider.element.classList.add("slider-draggable");
    this.slider.addEventListener("mousedown", this.startDragging);
    window.addEventListener("mouseup", this.stopDragging, { capture: true });
  }

  disable() {
    this.slider!.element.classList.remove("slider-draggable");

    clearTimeout(this.disableTimeout);
    this.slider!.removeEventListener("mousedown", this.startDragging);
    window.removeEventListener("mouseup", this.stopDragging, { capture: true });

    this.slider = undefined;
    this.disableTimeout = undefined;
    this.lastX = undefined;
  }

  /**
   * Scroll the slider the appropriate amount of pixels and update the last event position
   */
  mouseMove(event: MouseEvent) {
    if (!this.slider) return;
    const distance = this.lastX! - event.clientX;
    this.lastX = event.clientX;
    this.slider.element.scrollLeft += distance;
  }

  /**
   * Clear disable timeout, set up variables and styles and attach the listener
   */
  startDragging(event: MouseEvent) {
    event.preventDefault();
    clearTimeout(this.disableTimeout);

    this.startX = this.lastX = event.clientX;
    this.slider!.element.style.scrollBehavior = "auto";
    this.slider!.element.style.scrollSnapStop = "unset";
    this.slider!.element.style.scrollSnapType = "none";
    this.slider!.element.classList.add("slider-dragging");

    window.addEventListener("mousemove", this.mouseMove);
  }

  /**
   * Remove listener and clean up the styles
   * Note: We first restore the smooth behaviour, then manually snap to the current slide
   * Using a timeout, we then restore the rest of the snap behaviour
   */
  stopDragging(event: MouseEvent) {
    if (this.lastX === undefined) return;

    event.preventDefault();

    const finalSlide = this.getFinalSlide();

    window.removeEventListener("mousemove", this.mouseMove);
    this.lastX = undefined;
    this.slider!.element.style.scrollBehavior = "";
    this.slider!.element.classList.remove("slider-dragging");

    this.slider!.slideTo(finalSlide);

    this.disableTimeout = (setTimeout as (typeof window)["setTimeout"])(() => {
      this.slider!.element.style.scrollSnapStop = "";
      this.slider!.element.style.scrollSnapType = "";
    }, 300);
  }

  getFinalSlide() {
    if (!this.quickSwipeDistance) {
      return this.slider!.slide;
    }

    const distance = Math.abs(this.startX! - this.lastX!);
    const minimumNotReached = this.quickSwipeDistance > distance;
    const halfPointCrossed = distance > this.slider!.getSlideWidth() / 2;

    if (minimumNotReached || halfPointCrossed) {
      return this.slider!.slide;
    }

    if (this.startX! < this.lastX!) {
      return this.slider!.slide - 1;
    }

    return this.slider!.slide + 1;
  }
}

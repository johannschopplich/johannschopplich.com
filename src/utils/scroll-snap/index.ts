import type { ScrollSnapPlugin } from "./plugin";

export * from "./draggable";

/**
 * Refactored version of scroll-snap-slider
 *
 * @see https://github.com/barthy-koeln/scroll-snap-slider
 * @license MIT
 */
export class ScrollSnapSlider {
  /** Base element of this slider */
  public element: HTMLElement;

  /** Active slide */
  public slide: number;

  /** Timeout delay in milliseconds used to catch the end of scroll events */
  public scrollTimeout = 100;

  /** Adds event listener to the element */
  public addEventListener: typeof HTMLElement.prototype.addEventListener;

  /** Removes event listener from the element */
  public removeEventListener: typeof HTMLElement.prototype.removeEventListener;

  /** Active slide's `scrollLeft` in the containing element */
  private slideScrollLeft: number;

  /** Timeout ID used to catch the end of scroll events */
  private scrollTimeoutId: number | undefined;

  /** Maps a plugin name to its instance */
  private plugins = new Map<string, ScrollSnapPlugin>();

  constructor(
    element: HTMLElement,
    {
      plugins = [],
    }: {
      plugins?: ScrollSnapPlugin[];
    } = {}
  ) {
    this.element = element;
    this.slideScrollLeft = this.element.scrollLeft;
    this.slide = this.calculateSlide();

    this.addEventListener = this.element.addEventListener.bind(this.element);
    this.removeEventListener = this.element.removeEventListener.bind(
      this.element
    );

    this.onScroll = this.onScroll.bind(this);
    this.onScrollEnd = this.onScrollEnd.bind(this);
    this.slideTo = this.slideTo.bind(this);

    this.addEventListener("scroll", this.onScroll, { passive: true });

    for (const plugin of plugins) {
      this.plugins.set(plugin.id, plugin);
      plugin.enable(this);
    }
  }

  /**
   * Calculate the current slide width
   */
  public getSlideWidth() {
    return (this.element.firstElementChild as HTMLElement).offsetWidth;
  }

  /**
   * Scroll to a slide by index
   */
  public slideTo(index: number) {
    this.element.scrollTo({
      left: index * this.getSlideWidth(),
    });
  }

  /**
   * Free resources and listeners, disable plugins
   */
  public destroy() {
    this.removeEventListener("scroll", this.onScroll);
    clearTimeout(this.scrollTimeoutId);

    for (const plugin of this.plugins.values()) {
      plugin.disable();
    }
  }

  /**
   * Act when scrolling starts and stops
   */
  private onScroll() {
    if (this.scrollTimeoutId === undefined) {
      const direction = this.element.scrollLeft > this.slideScrollLeft ? 1 : -1;
      this.dispatch("slide-start", this.slide + direction);
    }

    const floored = this.calculateSlide();
    if (floored !== this.slide) {
      this.slideScrollLeft = this.element.scrollLeft;
      this.slide = floored;
      this.dispatch("slide-pass", this.slide);
    }

    clearTimeout(this.scrollTimeoutId);
    this.scrollTimeoutId = (setTimeout as (typeof window)["setTimeout"])(
      this.onScrollEnd,
      this.scrollTimeout
    );
  }

  /**
   * Calculate all necessary things and dispatch an event when sliding stops
   */
  private onScrollEnd() {
    this.scrollTimeoutId = undefined;
    this.slide = this.calculateSlide();
    this.slideScrollLeft = this.element.scrollLeft;
    this.dispatch("slide-stop", this.slide);
  }

  /**
   * Calculates the active slide
   *
   * The scroll-snap-type property makes sure that the container snaps
   * perfectly to integer multiples
   */
  private calculateSlide() {
    return Math.round(this.element.scrollLeft / this.getSlideWidth());
  }

  /**
   * Dispatch a custom event
   */
  private dispatch(event: string, detail: any) {
    return this.element.dispatchEvent(new CustomEvent(event, { detail }));
  }
}

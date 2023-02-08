import type { ScrollSnapSlider } from ".";

export class ScrollSnapPlugin {
  get id() {
    return this.constructor.name;
  }

  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  public enable(slider: ScrollSnapSlider) {}

  public disable() {}
}

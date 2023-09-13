import { createAnimere } from "animere";

export function install() {
  createAnimere({
    shouldInitialize: () => "animatable" in document.documentElement.dataset,
  });
}

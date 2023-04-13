import Animere from "animere";

export function install() {
  new Animere({
    shouldInitialize: () => "animatable" in document.documentElement.dataset,
  });
}

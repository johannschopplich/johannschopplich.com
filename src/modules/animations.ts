import Animere from "animere";

export function install() {
  new Animere({
    initResolver: () => !!document.documentElement.dataset.animatable,
  });
}

import Animere from "animere";

export const install = () => {
  new Animere({
    initResolver: () => !!document.documentElement.dataset.animatable,
  });
};

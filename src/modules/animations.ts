import Animere from "animere";

export const install = () => {
  new Animere({
    skipInit: () => !document.documentElement.dataset.animatable,
  });
};

import Animere from "animere";

export const install = () => {
  new Animere({
    skipInit: () =>
      window.matchMedia("(prefers-reduced-motion: reduce)").matches,
  });
};

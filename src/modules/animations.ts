import Animere from "animere";

export const install = () => {
  new Animere({
    disallowInit: () =>
      window.matchMedia("(prefers-reduced-motion: reduce)").matches,
  });
};

import Loadeer from "loadeer";

export const install = () => {
  const loadeer = new Loadeer();
  loadeer.observe();
};

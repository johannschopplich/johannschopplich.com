import Loadeer from "loadeer";

export const install = () => {
  const loadeer = new Loadeer('[data-loading="lazy"]');
  loadeer.observe();
};

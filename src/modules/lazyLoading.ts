import Loadeer from "loadeer";

export function install() {
  const loadeer = new Loadeer('[data-loading="lazy"]');
  loadeer.observe();
}

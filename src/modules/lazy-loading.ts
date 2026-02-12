import { lazyLoad } from "unlazy";

export function install() {
  lazyLoad('img[loading="lazy"]');
}

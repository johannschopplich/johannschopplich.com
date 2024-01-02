import { setup as setupLiteYouTubeEmbed } from "../components/lite-youtube-embed";
import { setup as setupMasonryGrid } from "../components/masonry-grid";
import { setup as setupSparklyText } from "../components/sparkly-text";

export function install() {
  setupLiteYouTubeEmbed();
  setupMasonryGrid();
  setupSparklyText();
}

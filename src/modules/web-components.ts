import { setup as setupLiteYouTubeEmbed } from "../components/lite-youtube-embed";
import { setup as setupMasonryGrid } from "../components/masonry-grid";

export function install() {
  setupLiteYouTubeEmbed();
  setupMasonryGrid();
}

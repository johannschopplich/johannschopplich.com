import { setup as setupLiteYouTubeEmbed } from "../components/lite-youtube-embed";
import { setup as setupMasonryGrid } from "../components/masonry-grid";
import { setup as setupScrambleText } from "../components/scramble-text";
import { setup as setupSparklyText } from "../components/sparkly-text";

export function install() {
  setupLiteYouTubeEmbed();
  setupMasonryGrid();
  setupScrambleText({
    cursorFontUrl: "/assets/fonts/woff2/IosevkaAile-SemiBold.woff2",
    cursorFontFamily: "Cooper Hewitt",
  });
  setupSparklyText();
}

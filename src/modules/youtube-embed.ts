import { LiteYouTubeEmbed } from "../components/lite-youtube-embed";

export function install() {
  customElements.define("lite-youtube", LiteYouTubeEmbed);
}

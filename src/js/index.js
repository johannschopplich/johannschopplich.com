import "../scss/index.scss";

import "./partials/lazyLoading";
import "./partials/registerServiceWorker";
import "./partials/templateHandler";
import "./partials/theming";

import Animere from "animere";
import MasonryGrid from "./components/masonryGrid";
import MediumZoom from "./components/mediumZoom";

new Animere();
new MasonryGrid();
new MediumZoom();

import "../scss/index.scss";

import "./partials/lazyLoading";
import "./partials/theming";
import "./partials/registerServiceWorker";

import Animere from "animere";
import MasonryGrid from "../components/masonryGrid";
import MediumZoom from "../components/mediumZoom";

new Animere();
new MasonryGrid();
new MediumZoom();

const templateModules = import.meta.glob("./templates/*.js");
const currentTemplate = `./templates/${document.body.dataset.template}.js`;

(async () => {
  if (!(currentTemplate in templateModules)) return;

  const { default: Component } = await templateModules[currentTemplate]();
  await Component();
})();

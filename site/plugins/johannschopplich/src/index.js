import SeoPreview from "./components/SeoPreview.vue";
import "./index.css";

window.panel.plugin("johannschopplich/website", {
  sections: {
    "seo-preview": SeoPreview,
  },
});

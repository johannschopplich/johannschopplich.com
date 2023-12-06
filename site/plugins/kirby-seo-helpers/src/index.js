import SeoPreview from "./components/SeoPreview.vue";
import ContentTranslation from "./components/ContentTranslation.vue";
import "./index.css";

window.panel.plugin("johannschopplich/website", {
  sections: {
    "seo-preview": SeoPreview,
    "content-translation": ContentTranslation,
  },
});

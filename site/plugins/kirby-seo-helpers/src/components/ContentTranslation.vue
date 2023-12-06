<script>
import SectionMixin from "../mixins/section.js";
import LocaleMixin from "../mixins/locale.js";
import TranslationMixin from "../mixins/translation.js";

export default {
  mixins: [SectionMixin, LocaleMixin, TranslationMixin],

  data() {
    return {
      label: undefined,
      config: {},
      defaultLanguage: this.$languages.find((lang) => lang.default).code,
      defaultContent: {},
    };
  },

  computed: {
    currentContent() {
      return this.$store.getters["content/values"]();
    },
    translatableContent() {
      return Object.fromEntries(
        Object.entries(this.currentContent).filter(([key]) =>
          this.config.translatableFields.includes(key),
        ),
      );
    },
    syncableContent() {
      return Object.fromEntries(
        Object.entries(this.defaultContent).filter(([key]) =>
          this.config.syncableFields.includes(key),
        ),
      );
    },
  },

  async created() {
    const response = await this.load();
    this.label =
      typeof response.label === "string"
        ? response.label
        : response.label?.[this.$language?.code];
    this.config = response.config;

    const updateDefaultContent = async () => {
      this.defaultContent = await this.getDefaultContent();
    };
    // Re-fetch default content whenever the page gets saved
    this.$events.$on("model.update", updateDefaultContent);
    updateDefaultContent();
  },

  methods: {
    sync() {
      for (const [key, value] of Object.entries(this.syncableContent)) {
        this.$store.dispatch("content/update", [key, value]);
      }

      this.$panel.notification.success(`${this.$t("confirm")}!`);
    },
    async translate(language) {
      this.$panel.view.isLoading = true;

      const clone = JSON.parse(JSON.stringify(this.translatableContent));
      try {
        await this.recursiveTranslateContent(clone, {
          targetLanguage: language.code,
          translatableBlocks: this.config.translatableBlocks,
        });
      } catch (error) {
        console.error(error);
        this.$panel.notification.success(this.$t("error"));
        return;
      }

      // Update content
      for (const [key, value] of Object.entries(clone)) {
        this.$store.dispatch("content/update", [key, value]);
      }

      this.$panel.view.isLoading = false;
      this.$panel.notification.success(`${this.$t("confirm")}!`);
    },
    async getDefaultContent() {
      const { content } = await this.$api.get(this.$view.path, {
        language: this.defaultLanguage,
      });
      return content;
    },
  },
};
</script>

<template>
  <k-section :headline="label">
    <k-box v-if="!config?.DeepL?.apiKey" theme="none">
      <k-text>
        You need to set the
        <code>johannschopplich.seo-helpers.DeepL.apiKey</code>
        option in your <code>config.php</code>.
      </k-text>
    </k-box>
    <k-box v-else theme="none">
      <k-button-group layout="collapsed">
        <k-button
          :disabled="$language.default"
          size="sm"
          variant="filled"
          @click="sync()"
        >
          {{ $t("johannschopplich.seo-helpers.sync") }}
        </k-button>
        <k-button
          v-for="language in $languages.filter((lang) => !lang.default)"
          :key="language.code"
          :disabled="$language.default"
          icon="translate"
          size="sm"
          variant="filled"
          theme="notice"
          @click="translate(language)"
        >
          {{
            $t("johannschopplich.seo-helpers.translate", {
              language: language.code.toUpperCase(),
            })
          }}
        </k-button>
      </k-button-group>
    </k-box>
  </k-section>
</template>

<script>
import { joinURL, withLeadingSlash } from "ufo";
import SectionMixin from "../mixins/section.js";
import LocaleMixin from "../mixins/locale.js";

export default {
  mixins: [SectionMixin, LocaleMixin],

  data() {
    return {
      label: undefined,
      config: {},
      site: {},
      url: "",
    };
  },

  computed: {
    content() {
      return this.$store.getters["content/values"]();
    },
    path() {
      if (!this.url) return "";

      let path = this.getNonLocalizedPath(this.url);

      // Add language prefix only if it's not the default language
      if (!this.$language.default) {
        path = joinURL(this.$language.code, path);
      }

      return withLeadingSlash(path);
    },
  },

  watch: {
    "$language.code": {
      async handler() {
        const { url } = await this.$api.get(this.$view.path);
        this.url = url;
      },
      immediate: true,
    },
  },

  async created() {
    const response = await this.load();
    this.label = response.label;
    this.config = response.config;
    this.site = response.site;
  },

  methods: {
    joinURL,
  },
};
</script>

<template>
  <k-section :headline="label">
    <div
      class="mb-2 overflow-hidden rounded-[var(--input-rounded)] bg-[var(--input-color-back)] p-4"
    >
      <div class="mb-2 flex gap-3 items-center">
        <figure
          class="bg-[#f1f3f4] border-solid border-[#ecedef] inline-flex aspect-square h-[26px] w-[26px] items-center justify-center rounded-full"
        >
          <img
            class="block h-[18px] w-[18px]"
            :src="config.faviconUrl || '/assets/favicon.svg'"
            alt=""
          />
        </figure>
        <div class="flex flex-col">
          <span class="text-[#4d5156] text-sm">{{ site.title }}</span>
          <span class="text-[#4d5156] line-clamp-1 text-xs">{{
            joinURL(config.baseUrl || site.url, path)
          }}</span>
        </div>
      </div>

      <h3 class="text-[#1a0dab] mb-1 line-clamp-1 text-xl">
        {{ content.customTitle || `${$view.title} – ${site.title}` }}
      </h3>

      <p class="text-[#4d5156] line-clamp-2 text-sm">
        {{ content.description }}
      </p>
    </div>
  </k-section>
</template>

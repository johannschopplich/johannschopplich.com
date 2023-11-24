export default {
  computed: {
    locales() {
      return this.$languages.reduce(
        (locales, language) => [...locales, language.code],
        [],
      );
    },
  },
  methods: {
    getNonLocalizedPath(url) {
      const _url = new URL(url);
      const parts = _url.pathname.split("/").filter(Boolean);

      // Remove locale prefix from path
      if (this.locales.includes(parts[0])) {
        parts.shift();
      }

      return `/${parts.join("/")}`;
    },
  },
};

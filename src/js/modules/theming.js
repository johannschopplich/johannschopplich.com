export const install = () => {
  const root = document.documentElement;
  const themes = ["light", "dark"];

  // Handle switching themes
  document.querySelector("#theme-switcher").addEventListener("click", () => {
    const currentIndex = themes.findIndex(
      (i) => (root.dataset.theme || "light") === i
    );
    const newIndex = currentIndex === 0 ? 1 : 0;

    root.dataset.theme = themes[newIndex];
    localStorage.setItem("color-schema", themes[newIndex]);
  });

  // Handle themed containers
  const inverseContainers = document.querySelectorAll("[data-theme-inverse]");
  const handleInvertedContainers = (elements) => {
    for (const element of elements) {
      if (root.dataset.theme === "dark") {
        element.classList.add("bg-contrast-lowest");
        delete element.dataset.theme;
      } else {
        element.dataset.theme = "dark";
        element.classList.remove("bg-contrast-lowest");
      }
    }
  };

  handleInvertedContainers(inverseContainers);

  const themeObserver = new MutationObserver((mutations) => {
    for (const mutation of mutations) {
      if (mutation.attributeName === "data-theme") {
        handleInvertedContainers(inverseContainers);
      }
    }
  });

  themeObserver.observe(document.documentElement, {
    attributes: true,
    childList: false,
    characterData: false,
  });
};

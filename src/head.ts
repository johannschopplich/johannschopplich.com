const root = document.documentElement;

if (matchMedia("(hover: none)").matches) {
  root.style.setProperty("--vh", `${window.innerHeight * 0.01}px`);
}

if (
  !matchMedia("(prefers-reduced-motion: reduce)").matches &&
  !/(gle|ing|ro)bot|crawl|spider/i.test(navigator.userAgent)
) {
  root.dataset.animatable = "true";
}

// const prefersDark = matchMedia("(prefers-color-scheme: dark)").matches;
const setting = localStorage.getItem("color-schema");

// if (setting === "dark" || (prefersDark && setting !== "light")) {
//   root.dataset.theme = "dark";
// }

if (setting === "dark") {
  root.dataset.theme = "dark";
}

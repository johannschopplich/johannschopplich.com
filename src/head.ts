const root = document.documentElement;
const prefersDark = matchMedia("(prefers-color-scheme: dark)").matches;
const setting = localStorage.getItem("color-schema") || "auto";

if (setting === "dark" || (prefersDark && setting !== "light")) {
  root.dataset.theme = "dark";
}

if (
  !matchMedia("(prefers-reduced-motion: reduce)").matches &&
  !/(gle|ing|ro)bot|crawl|spider/i.test(navigator.userAgent)
) {
  root.dataset.animatable = "true";
}

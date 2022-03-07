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

const setting = localStorage.getItem("color-schema");
if (setting) root.dataset.theme = setting;

const prefersDark = matchMedia("(prefers-color-scheme: dark)").matches;
const setting = localStorage.getItem("color-schema") || "auto";

if (setting === "dark" || (prefersDark && setting !== "light")) {
  document.documentElement.dataset.theme = "dark";
}

if (!window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
  const style = document.createElement("style");
  style.innerHTML = "[data-animere] { visibility: hidden }";
  document.head.appendChild(style);
}

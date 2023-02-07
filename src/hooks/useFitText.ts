export function useFitText(selector = "[data-split-text]") {
  const elements = [...document.querySelectorAll<HTMLElement>(selector)];
  if (elements.length === 0) return;

  for (const element of elements) {
    splitText(element);
  }
}

function splitText(element: HTMLElement) {
  const html = element.innerHTML;
  element.innerHTML = "";

  for (const letter of html.split("")) {
    const span = document.createElement("span");
    span.innerHTML = letter.charCodeAt(0) === 32 ? "&nbsp;" : letter;
    element.appendChild(span);
  }

  element.classList.add("ready");
}

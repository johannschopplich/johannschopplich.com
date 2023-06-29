export function applySplitText(selector = "[data-split-text]") {
  const elements = [...document.querySelectorAll<HTMLElement>(selector)];
  if (elements.length === 0) return;

  for (const element of elements) {
    splitText(element);
  }
}

function splitText(element: HTMLElement) {
  const html = element.innerHTML;
  let newHtml = "";

  for (const letter of html.split("")) {
    const spanContent = letter.charCodeAt(0) === 32 ? "&nbsp;" : letter;
    newHtml += `<span>${spanContent}</span>`;
  }

  element.innerHTML = newHtml;
  element.dataset.splitTextReady = "";
}

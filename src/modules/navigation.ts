export function install() {
  const header = document.querySelector("header");
  if (!header) return;

  const observer = new IntersectionObserver(
    ([entry]) =>
      entry.target.classList.toggle("is-pinned", entry.intersectionRatio < 1),
    {
      threshold: [1],
    },
  );

  observer.observe(header);
}

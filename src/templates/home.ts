export default async () => {
  const { Draggable } = await import("@neodrag/vanilla");
  const element = document.querySelector<HTMLElement>(".draggable");
  if (!element) return;

  const dragInstance = new Draggable(element);
};

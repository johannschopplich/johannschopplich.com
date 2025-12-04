const TADA_KEYFRAMES: Keyframe[] = [
  { transform: "scaleX(1)", offset: 0 },
  { transform: "scale3d(0.9, 0.9, 0.9) rotate(-3deg)", offset: 0.1 },
  { transform: "scale3d(0.9, 0.9, 0.9) rotate(-3deg)", offset: 0.2 },
  { transform: "scale3d(1.1, 1.1, 1.1) rotate(3deg)", offset: 0.3 },
  { transform: "scale3d(1.1, 1.1, 1.1) rotate(-3deg)", offset: 0.4 },
  { transform: "scale3d(1.1, 1.1, 1.1) rotate(3deg)", offset: 0.5 },
  { transform: "scale3d(1.1, 1.1, 1.1) rotate(-3deg)", offset: 0.6 },
  { transform: "scale3d(1.1, 1.1, 1.1) rotate(3deg)", offset: 0.7 },
  { transform: "scale3d(1.1, 1.1, 1.1) rotate(-3deg)", offset: 0.8 },
  { transform: "scale3d(1.1, 1.1, 1.1) rotate(3deg)", offset: 0.9 },
  { transform: "scaleX(1)", offset: 1 },
];

export default async function () {
  const sticker = document.querySelector<HTMLElement>('[data-sticker="svg"]');
  if (!sticker) return;

  setTimeout(async () => {
    await sticker.animate(TADA_KEYFRAMES, {
      duration: 800,
    }).finished;
  }, 100);
}

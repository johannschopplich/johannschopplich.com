import type { ScrambleText } from "../components/scramble-text";
import { prefersReducedMotion } from "../components/_shared";

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

const INTRO_DELAY_MS = 700;
const STAGGER_MS = 200;

const sleep = (ms: number) => new Promise<void>((r) => setTimeout(r, ms));

export default async function () {
  if (prefersReducedMotion.matches) return;

  const nodes = document.querySelectorAll<ScrambleText>(
    "h1.headline scramble-text",
  );
  if (nodes.length === 0) return;

  await sleep(INTRO_DELAY_MS);

  await Promise.all(
    [...nodes].map((el, i) => sleep(i * STAGGER_MS).then(() => el.play())),
  );

  document
    .querySelector<HTMLElement>('[data-sticker="svg"]')
    ?.animate(TADA_KEYFRAMES, { duration: 800 });
}

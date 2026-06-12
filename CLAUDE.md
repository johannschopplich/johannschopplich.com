# johannschopplich.com

Personal website: Kirby CMS (PHP) + Vite + UnoCSS (preset-wind4) + TypeScript.
Package manager: pnpm.

- UnoCSS class order: shortcuts/marker utilities (`headline`, `hyphenate`, …) →
  `[--var:…]` assignments → position/layout → display/flex/grid → spacing →
  sizing → typography → colors/background → border → effects/transitions →
  `after:`/`before:` block → state variants last. Responsive overrides stay
  next to their base class (`py-xl md:py-5xl`); loose ordering within groups.

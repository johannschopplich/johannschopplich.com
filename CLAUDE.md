# johannschopplich.com

Personal website: Kirby CMS (PHP) + Vite + UnoCSS (preset-wind4) + TypeScript.
Package manager: pnpm.

- UnoCSS class order: shortcuts/marker utilities (`headline`, `hyphenate`, …) →
  `[--var:…]` assignments → position/layout → display/flex/grid → spacing →
  sizing → typography → colors/background → border → effects/transitions →
  `after:`/`before:` block → state variants → breakpoint block last. Everything
  with a breakpoint prefix (`md:px-gutter`, `max-md:text-sm`) gathers at the
  very end of the string: `max-*` first, then ascending `sm:` → `md:` → `lg:` →
  `xl:`; within one breakpoint loosely follow the base group order, with
  breakpoint+state combos at the end of that run. `dark:` variants stay next
  to their base class (`border-contrast-low dark:border-contrast-lower`).
  Loose ordering within groups.

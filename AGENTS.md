# johannschopplich.com

Personal website of Johann Schopplich.

## Commands

```bash
pnpm dev                           # frontend only; PHP on Herd or `composer start`
pnpm build
pnpm lint
pnpm format:check
pnpm test:types
scripts/sync-content.sh --upload   # dry run; --apply rsyncs storage/content (not in git), --download pulls
```

## Conventions

- UnoCSS class order: shortcuts/marker utilities (`headline`, `hyphenate`,
  icon utilities like `i-tabler-arrow-right`, …) →
  `[--var:…]` assignments → position/layout → display/flex/grid → spacing →
  sizing → typography → colors/background → border → effects/transitions →
  `after:`/`before:` block → state variants → breakpoint block last. Everything
  with a breakpoint prefix (`md:px-gutter`, `max-md:text-sm`) gathers at the
  very end of the string: `max-*` first, then ascending `sm:` → `md:` → `lg:` →
  `xl:`; within one breakpoint loosely follow the base group order, with
  breakpoint+state combos at the end of that run. `dark:` variants stay next
  to their base class (`border-contrast-low dark:border-contrast-lower`).
  Loose ordering within groups.

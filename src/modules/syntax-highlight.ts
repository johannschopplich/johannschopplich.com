// Section references are to the TOON specification: https://github.com/toon-format/spec

// Every member needs a `::highlight(syntax-…)` rule in `src/styles/components/syntax.css`.
type Category = "key" | "count" | "punctuation" | "comment";

interface Rule {
  pattern: RegExp;
  category?: Category;
  captures?: Record<number, Category>;
  rules?: Rule[];
}

interface Match {
  rule: Rule;
  indices: RegExpIndicesArray;
}

// Field entries are keys, split at every nesting level by the active delimiter
// (§6). A quoted name may carry a brace or a delimiter, so it matches first –
// §6 requires brace matching to ignore braces inside quoted names.
const fieldRules: Rule[] = [
  { pattern: /"(?:\\.|[^"\\\r\n])*"/dg, category: "key" },
  { pattern: /[{},|\t]/dg, category: "punctuation" },
  { pattern: /[^{},|\t]+/dg, category: "key" },
];

const toonRules: Rule[] = [
  // Only spaces may precede the `#`, and there is no inline form (§5.1).
  { pattern: /^ *#.*$/dgm, category: "comment" },
  // A quoted key is recognized by what follows it, not by its content (§5.2).
  { pattern: /"(?:\\.|[^"\\\r\n])*"(?=[^\S\r\n]*[:[])/dg, category: "key" },
  // A bracket segment carries a length, the keyed colon and the delimiter (§6);
  // `[03]` is none. The optional length is what paints the empty-array value
  // form `key: []` (§9.1), and with it `key[]:` and `[:]`, which §6 rejects.
  { pattern: /\[(?:0|[1-9]\d*)?:?[|\t]?\]/dg, category: "count" },
  // Unquoted key, `^[A-Za-z_][A-Za-z0-9_.]*$` (§7.3), optionally behind a list
  // marker, plus the hyphen of `foo-bar: 1` (§7.4). The rest of §7.4's decoder
  // tolerance is not followed: `2fa: true` keeps its key unpainted.
  {
    pattern: /^ *(- )?([a-z_][\w.-]*)(?=[^\S\r\n]*[:[])/dgim,
    captures: { 1: "punctuation", 2: "key" },
  },
  // A field list always follows the bracket segment, which is what the lookbehind
  // pins it to (§6). Its interior is scanned again so that nesting, which the
  // ABNF allows to any depth, needs no rule of its own.
  { pattern: /(?<=\])\{[^\r\n]*\}(?=:[^\S\r\n]*$)/dgm, rules: fieldRules },
  // Not a token: values carry no color, but the span has to be consumed so the
  // punctuation rule cannot reach a delimiter inside a string.
  { pattern: /"(?:\\.|[^"\\\r\n])*"/dg },
  // A list item carries `- ` or the bare marker (§5.2).
  { pattern: /^ *(- |-$)/dgm, captures: { 1: "punctuation" } },
  // Known limit: whether one of these is structural depends on position and on
  // the header's active delimiter (§6), and this tokenizer carries no scope
  // state. It paints the pipe in `tags[2]: a|b,c`, the second colon of
  // `note: 12:30` and the braces of `key: {x}`, which are all data.
  { pattern: /[:,|{}]/dg, category: "punctuation" },
];

// Adapted from microlighter's `json.js` (MIT, Dave Rupert), which references
// Microsoft VS Code's `JSON.tmLanguage.json` (MIT). The bare string pattern is
// the same guard as in TOON.
const jsonRules: Rule[] = [
  { pattern: /"(?:\\.|[^"\\\r\n])*"(?=\s*:)/dg, category: "key" },
  { pattern: /"(?:\\.|[^"\\\r\n])*"/dg },
  { pattern: /[{}[\],:]/dg, category: "punctuation" },
];

const grammars: Record<string, Rule[]> = {
  toon: toonRules,
  json: jsonRules,
};

export function install() {
  if (!CSS.highlights) return;

  const blocks = document.querySelectorAll<HTMLElement>(
    'pre > code[class*="language-"]',
  );
  if (blocks.length === 0) return;

  const rangesByCategory = new Map<Category, Range[]>();

  for (const block of blocks) {
    const language = /language-(\S+)/.exec(block.className)?.[1];
    const rules = language ? grammars[language] : undefined;
    if (!rules) continue;

    // The Highlight API addresses offsets inside a single text node.
    block.normalize();
    const node = block.firstChild;
    if (node?.nodeType !== Node.TEXT_NODE || node.nextSibling) continue;

    tokenize(node as Text, rules, rangesByCategory);
  }

  for (const [category, ranges] of rangesByCategory) {
    CSS.highlights.set(`syntax-${category}`, new Highlight(...ranges));
  }
}

function tokenize(
  node: Text,
  rules: Rule[],
  rangesByCategory: Map<Category, Range[]>,
) {
  scan(node.data, rules, 0);

  function addRange(
    [start, end]: [number, number],
    category: Category,
    offset: number,
  ) {
    if (start === end) return;

    const range = new Range();
    range.setStart(node, offset + start);
    range.setEnd(node, offset + end);

    const existingRanges = rangesByCategory.get(category);
    if (existingRanges) existingRanges.push(range);
    else rangesByCategory.set(category, [range]);
  }

  function scan(text: string, rules: Rule[], offset: number) {
    let cursor = 0;

    while (cursor < text.length) {
      let earliestMatch: Match | undefined;

      for (const rule of rules) {
        rule.pattern.lastIndex = cursor;
        const indices = rule.pattern.exec(text)?.indices;
        if (
          indices &&
          (!earliestMatch || indices[0]![0] < earliestMatch.indices[0]![0])
        ) {
          earliestMatch = { rule, indices };
        }
      }

      if (!earliestMatch) return;

      const { rule, indices } = earliestMatch;
      const [start, end] = indices[0]!;

      if (rule.category) addRange(indices[0]!, rule.category, offset);
      if (rule.rules) scan(text.slice(start, end), rule.rules, offset + start);

      for (const [groupIndex, category] of Object.entries(
        rule.captures ?? {},
      )) {
        const span = indices[Number(groupIndex)];
        if (span) addRange(span, category, offset);
      }

      // Zero-width matches would otherwise stall the scan.
      cursor = Math.max(end, cursor + 1);
    }
  }
}

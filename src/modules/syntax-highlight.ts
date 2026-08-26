type Category = "key" | "count" | "punct" | "comment";

interface Rule {
  pattern: RegExp;
  category?: Category;
  captures?: Record<number, Category>;
}

const toonRules: Rule[] = [
  // Only spaces may precede the `#`, and there is no inline form (§5.1).
  { pattern: /^ *#.*$/dgm, category: "comment" },
  // A quoted key is recognized by what follows it, not by its content (§5.2).
  { pattern: /"(?:\\.|[^"\\\r\n])*"(?=[^\S\r\n]*[:[])/dg, category: "key" },
  // Bracket segment: length, keyed colon, delimiter (§6) – `[03]` is not one.
  // Empty brackets are the array value form (§9.1), painted anyway.
  { pattern: /\[(?:0|[1-9]\d*)?:?[|\t]?\]/dg, category: "count" },
  // Unquoted key, `^[A-Za-z_][A-Za-z0-9_.]*$` (§7.3), optionally behind a list
  // marker. Hyphens are tolerated: §7.4 makes `foo-bar: 1` valid input.
  {
    pattern: /^ *(- )?([a-z_][\w.-]*)(?=[^\S\r\n]*[:[])/dgim,
    captures: { 1: "punct", 2: "key" },
  },
  // The whole field list reads as one key (§6); a field list always follows the
  // bracket segment, which is what the lookbehind pins it to. Painting field
  // names individually would mean knowing whether a brace sits inside a quoted
  // name, and §6 requires exactly that distinction.
  { pattern: /(?<=\])\{[^\r\n]*\}(?=:[^\S\r\n]*$)/dgm, category: "key" },
  // Not a token: values carry no color, but the span has to be consumed so the
  // punctuation rule cannot reach a delimiter inside a string.
  { pattern: /"(?:\\.|[^"\\\r\n])*"/dg },
  // A list item carries `- ` or the bare marker (§5.2).
  { pattern: /^ *(- |-$)/dgm, captures: { 1: "punct" } },
  // Known limit: the active delimiter is declared per header (§6), so in a tab
  // or pipe scope a literal comma in row content is data, not punctuation. That
  // needs scope state this tokenizer does not carry.
  { pattern: /[:,|{}]/dg, category: "punct" },
];

// Adapted from microlighter's `json.js` (MIT, Dave Rupert), which references
// Microsoft VS Code's `JSON.tmLanguage.json` (MIT). The bare string pattern is
// the same guard as in TOON.
const jsonRules: Rule[] = [
  { pattern: /"(?:\\.|[^"\\\r\n])*"(?=\s*:)/dg, category: "key" },
  { pattern: /"(?:\\.|[^"\\\r\n])*"/dg },
  { pattern: /[{}[\],:]/dg, category: "punct" },
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
  const text = node.data;
  let cursor = 0;

  const addRange = ([start, end]: [number, number], category: Category) => {
    if (start === end) return;

    const range = new Range();
    range.setStart(node, start);
    range.setEnd(node, end);

    const existingRanges = rangesByCategory.get(category);
    if (existingRanges) existingRanges.push(range);
    else rangesByCategory.set(category, [range]);
  };

  while (cursor < text.length) {
    let bestMatch: { rule: Rule; indices: RegExpIndicesArray } | undefined;

    for (const rule of rules) {
      rule.pattern.lastIndex = cursor;
      const indices = rule.pattern.exec(text)?.indices;
      if (
        indices &&
        (!bestMatch || indices[0]![0] < bestMatch.indices[0]![0])
      ) {
        bestMatch = { rule, indices };
      }
    }

    if (!bestMatch) return;

    const { rule, indices } = bestMatch;
    if (rule.category) addRange(indices[0]!, rule.category);
    for (const [groupIndex, category] of Object.entries(rule.captures ?? {})) {
      const span = indices[Number(groupIndex)];
      if (span) addRange(span, category);
    }

    // Zero-width matches would otherwise stall the scan.
    cursor = Math.max(indices[0]![1], cursor + 1);
  }
}

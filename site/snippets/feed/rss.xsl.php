<?php echo '<?xml version="1.0" encoding="utf-8"?>' . "\n"; ?>
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:atom="http://www.w3.org/2005/Atom"
  exclude-result-prefixes="atom">
  <xsl:output method="html" encoding="utf-8" indent="yes" doctype-system="about:legacy-compat"/>

  <xsl:template match="/">
    <html>
      <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title><xsl:value-of select="/rss/channel/title"/> · RSS Feed</title>
        <style>
          :root {
            color-scheme: light dark;
            --fg:    light-dark(oklch(21.6% 0.006 56.043), oklch(97% 0.001 106.424));   /* stone-900 / 100 */
            --muted: light-dark(oklch(55.3% 0.013 58.071), oklch(70.9% 0.01 56.259));   /* stone-500 / 400 */
            --link:  light-dark(oklch(54.6% 0.245 262.881), oklch(70.7% 0.165 254.624)); /* blue-600 / 400 */
            --line:  light-dark(oklch(92.3% 0.003 48.717), oklch(26.8% 0.007 34.298));   /* stone-200 / 800 */
            --bg:    light-dark(#fff, oklch(14.7% 0.004 49.25));                         /* white / stone-950 */
          }
          html { background: var(--bg); }
          body {
            --gutter: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            inline-size: calc(100% - 2 * var(--gutter));
            max-inline-size: 36rem;
            margin-inline: auto;
            padding-block: 3rem 4rem;
            color: var(--fg);
            font-family: system-ui, sans-serif;
            line-height: 1.6;
          }
          :where(h1, p, ul, hr) { margin: 0; }
          .lede, .subscribe { color: var(--muted); }
          hr { border: 0; border-top: 1px solid var(--line); }
          ul { display: flex; flex-direction: column; gap: 1rem; padding: 0; list-style: none; }
          li a { color: var(--fg); font-weight: 600; text-decoration: none; }
          li a:hover { color: var(--link); text-decoration: underline; }
          li small { display: block; color: var(--muted); }
          footer a { color: var(--link); }
        </style>
      </head>
      <body>
        <h1><xsl:value-of select="/rss/channel/title"/></h1>
        <xsl:if test="/rss/channel/description">
          <p class="lede"><xsl:value-of select="/rss/channel/description"/></p>
        </xsl:if>
        <p class="subscribe">
          Subscribe by pasting this URL into your feed reader:<br/>
          <code><xsl:value-of select="/rss/channel/atom:link[@rel='self']/@href"/></code>
        </p>
        <hr/>
        <ul>
          <xsl:for-each select="/rss/channel/item">
            <!-- RFC-822 pubDate → ISO date: locate the 3-letter month in the
                 lookup string, its offset / 3 + 1 is the month number. -->
            <xsl:variable name="mm" select="format-number(string-length(substring-before('JanFebMarAprMayJunJulAugSepOctNovDec', substring(pubDate, 9, 3))) div 3 + 1, '00')"/>
            <li>
              <a href="{link}"><xsl:value-of select="title"/></a>
              <small>
                <time datetime="{concat(substring(pubDate, 13, 4), '-', $mm, '-', substring(pubDate, 6, 2))}">
                  <xsl:value-of select="substring(pubDate, 1, 16)"/>
                </time>
              </small>
            </li>
          </xsl:for-each>
        </ul>
        <footer>
          <a href="{/rss/channel/link}">← <xsl:value-of select="/rss/channel/link"/></a>
        </footer>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>

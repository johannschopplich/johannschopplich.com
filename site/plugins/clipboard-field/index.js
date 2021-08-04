window.panel.plugin('kirby-extended/clipboard-field', {
  fields: {
    clipboard: {
      extends: 'k-info-field',
      mounted () {
        // Copy text from buttons to clipboard
        this.$el.addEventListener('click', async ({ target }) => {
          if (!target.matches('button')) return

          try {
            // Clipboard API is available only for HTTPS pages (secure context)
            // Setting a browser flag can allow HTTP pages to be interpreted as secure
            await navigator.clipboard.writeText(target.textContent)
          } catch (error) {
            console.error(`Failed to copy "${target.textContent}" to clipboard`)
          }
        })
      }
    }
  }
})

/**
 * Triggers a download of a blob or file in the browser
 */
export function downloadFile(
  data: Blob | string,
  filename: string,
  mimeType?: string,
): void {
  const blob =
    typeof data === "string"
      ? new Blob([data], { type: mimeType || "text/plain" })
      : data;

  const url = URL.createObjectURL(blob);
  const anchor = document.createElement("a");
  anchor.href = url;
  anchor.download = filename;
  document.body.appendChild(anchor);
  anchor.click();
  document.body.removeChild(anchor);
  URL.revokeObjectURL(url);
}

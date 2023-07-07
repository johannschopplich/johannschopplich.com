export function debounce<T extends (...args: any[]) => void>(
  fn: T,
  delay: number,
) {
  let timeout: ReturnType<typeof setTimeout> | undefined;

  return function (...args: Parameters<T>) {
    if (timeout) clearTimeout(timeout);
    timeout = setTimeout(() => {
      timeout = undefined;
      fn(...args);
    }, delay);
  };
}

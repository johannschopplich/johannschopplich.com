/**
 * Debounces a function by a period of time
 *
 * @param {Function} fn The callback function to debounce
 * @param {number} [delay=250] The time to wait
 * @returns {Function} The debounced function representation
 */
export default function (fn, delay = 250) {
  let timeoutId;
  return (...args) => {
    if (timeoutId) clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
      timeoutId = null;
      fn(...args);
    }, delay);
  };
}

import Alpine from "alpinejs";

let isStartScheduled = false;

// Expose for DevTools debugging.
window.Alpine = Alpine;

/**
 * Returns the Alpine instance and schedules `Alpine.start()` to run
 * after all synchronous code completes. Safe to call multiple times.
 */
export function useAlpine() {
  if (!isStartScheduled) {
    isStartScheduled = true;
    queueMicrotask(() => Alpine.start());
  }

  return Alpine;
}

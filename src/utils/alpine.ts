import Alpine from "alpinejs";

let startScheduled = false;

// Expose for DevTools debugging
window.Alpine = Alpine;

/**
 * Returns the Alpine instance and schedules `Alpine.start()` to run
 * after all synchronous code completes. Safe to call multiple times.
 */
export function useAlpine() {
  if (!startScheduled) {
    startScheduled = true;
    queueMicrotask(() => Alpine.start());
  }

  return Alpine;
}

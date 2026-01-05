let alpinePromise: Promise<void> | undefined;

/**
 * Starts Alpine.js and returns a promise that resolves when Alpine is ready.
 * Ensures `Alpine.start()` is only called once, even if multiple modules
 * call this function. Await this before registering Alpine components.
 */
export function startAlpine(): Promise<void> {
  alpinePromise ??= Promise.resolve().then(() => {
    window.Alpine.start();
  });

  return alpinePromise;
}

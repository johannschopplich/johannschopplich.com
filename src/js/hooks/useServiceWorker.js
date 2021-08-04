/**
 * The new service worker waiting to be installed
 */
let newWorker;

/**
 * Registers a service worker
 *
 * @param {string} [swUrl="/service-worker.js"] Absolute URL for the worker to register
 * @param {object} [hooks={}] Object of hooks for registration events
 */
const register = async (swUrl = "/service-worker.js", hooks = {}) => {
  const { registrationOptions = {} } = hooks;
  delete hooks.registrationOptions;

  const emit = (hook, ...args) => {
    if (hooks && hooks[hook]) {
      hooks[hook](...args);
    }
  };

  try {
    const registration = await navigator.serviceWorker.register(
      swUrl,
      registrationOptions
    );
    emit("registered", registration);

    // Handle service worker updates
    // @see https://developers.google.com/web/fundamentals/primers/service-workers/lifecycle#handling_updates
    if (registration.waiting) {
      newWorker = registration.waiting;
      emit("updated", registration);
    } else {
      registration.addEventListener("updatefound", () => {
        emit("updatefound", registration);
        const installingWorker = registration.installing;

        // Handle state changes of new service worker
        installingWorker.addEventListener("statechange", () => {
          // Make sure new service worker installation is complete
          if (installingWorker.state !== "installed") return;

          if (navigator.serviceWorker.controller) {
            // At this point, the old content will have been purged and
            // the fresh content will have been added to the cache
            // Perfect time to notify the user that a new service worker
            // is ready to be installed
            newWorker = registration.waiting;
            emit("updated", registration);
          } else {
            // At this point, everything has been precached
            // Perfect time to notify the user that content is cached
            // for offline use
            emit("cached", registration);
          }
        });
      });
    }
  } catch (error) {
    if (!navigator.onLine) emit("offline");
    emit("error", error);
  }

  navigator.serviceWorker.ready.then((registration) => {
    emit("ready", registration);
  });
};

/**
 * Unregisters existing service workers
 */
const unregister = async () => {
  const registration = await navigator.serviceWorker.ready;
  registration.unregister();
};

/**
 * Handles service worker registration and updates
 *
 * @returns {object} Service worker related methods
 */
export default () => ({
  register,
  unregister,
  newWorker,
});

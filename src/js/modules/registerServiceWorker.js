/* eslint-disable no-unused-vars */
import { useServiceWorker } from "../hooks";

const { register, unregister } = useServiceWorker();

/**
 * A promise resolving when the document and all sub-resources have
 * finished loading
 */
const documentLoaded = new Promise((resolve) => {
  if (document.readyState === "complete") {
    resolve();
  } else {
    window.addEventListener("load", resolve);
  }
});

/**
 * Handle the service worker registration process
 */
export const install = async () => {
  if (!("serviceWorker" in navigator)) return;

  const hasExistingWorker = !!navigator.serviceWorker.controller;

  if (import.meta.env.VITE_SERVICE_WORKER === "true") {
    await documentLoaded;
    await register("/service-worker.js", {
      // registrationOptions: { scope: './' },
      ready(registration) {
        if (import.meta.env.DEV) console.log("Service worker is active.");
      },
      registered(registration) {
        if (import.meta.env.DEV)
          console.log("Service worker has been registered.");
      },
      cached(registration) {
        if (import.meta.env.DEV)
          console.log("Content has been cached for offline use.");
      },
      updatefound(registration) {
        if (import.meta.env.DEV) console.log("New content is downloading.");
      },
      updated(registration) {
        if (import.meta.env.DEV)
          console.log("New content is available; please refresh.");
        // document.dispatchEvent(
        //   new CustomEvent('swUpdated', { detail: registration })
        // )
      },
      offline() {
        if (import.meta.env.DEV)
          console.log(
            "No internet connection found. App is running in offline mode."
          );
      },
      error(error) {
        console.error("Error during service worker registration:", error);
      },
    });

    if (hasExistingWorker) {
      navigator.serviceWorker.controller.postMessage({ command: "trimCaches" });
    }
  } else if (hasExistingWorker) {
    unregister();
  }
};

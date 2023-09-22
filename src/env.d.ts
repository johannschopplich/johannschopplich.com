/// <reference types="vite/client" />

// Polyfill
interface Document {
  startViewTransition?: (callback: () => void) => void;
}

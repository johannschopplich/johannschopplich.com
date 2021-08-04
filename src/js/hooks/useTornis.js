/**
 * Limit a function to only run once every {delay}ms
 *
 * @param {number} delay The delay in ms
 * @param {Function} fn The function to throttle
 * @returns {Function} The throttled function
 */
function throttled(delay, fn) {
  let lastCall = 0;

  return function (...args) {
    const now = Date.now();
    if (now - lastCall < delay) return;
    lastCall = now;
    return fn(...args);
  };
}

/**
 * Main Tornis singleton class
 */
class Tornis {
  // Set a whole load of initial values
  constructor() {
    this.lastX = 0;
    this.lastY = 0;
    this.lastWidth = window.innerWidth;
    this.lastHeight = window.innerHeight;
    this.lastMouseX = 0;
    this.lastMouseY = 0;
    this.lastWindowX = window.screenX;
    this.lastWindowY = window.screenY;

    this.scrollHeight = document.body.scrollHeight;

    this.scrollChange = false;
    this.sizeChange = false;
    this.mouseChange = false;
    this.positionChange = false;

    this.currWidth = window.innerWidth;
    this.currHeight = window.innerHeight;
    this.currMouseX = 0;
    this.currMouseY = 0;

    // Flag to limit `requestAnimationFrame` renders
    this.updating = false;

    // Initialise the watched function queue
    this.callbacks = [];

    // Bind this to ease class methods
    this.update = this.update.bind(this);
    this.handleResize = this.handleResize.bind(this);
    this.handleMouse = this.handleMouse.bind(this);
    this.formatData = this.formatData.bind(this);
    this.watch = this.watch.bind(this);
    this.unwatch = this.unwatch.bind(this);

    // Throttled event handlers
    this.handleResize = throttled(100, this.handleResize);
    this.handleMouse = throttled(75, this.handleMouse);

    // Bind event handlers to the window
    window.addEventListener("resize", this.handleResize);
    window.addEventListener("mousemove", this.handleMouse);

    // Begin the update loop
    requestAnimationFrame(this.update);
  }

  /**
   * Event handler to capture screen size
   */
  handleResize() {
    this.currWidth = window.innerWidth;
    this.currHeight = window.innerHeight;
  }

  /**
   * Event handler to capture mouse position
   *
   * @param {Event} event The event taking place
   */
  handleMouse(event) {
    this.currMouseX = event.clientX;
    this.currMouseY = event.clientY;
  }

  /**
   * Returns a copy of the store data, formatted for public use
   *
   * @returns {object} The stored data
   */
  formatData() {
    return {
      scroll: {
        changed: this.scrollChange,
        left: Math.floor(this.lastX),
        right: Math.floor(this.lastX + this.lastWidth),
        top: Math.floor(this.lastY),
        bottom: Math.floor(this.lastY + this.lastHeight),
      },
      size: {
        changed: this.sizeChange,
        x: Math.floor(this.lastWidth),
        y: Math.floor(this.lastHeight),
        docY: Math.floor(this.scrollHeight),
      },
      mouse: {
        changed: this.mouseChange,
        x: Math.floor(this.lastMouseX),
        y: Math.floor(this.lastMouseY),
      },
      position: {
        changed: this.positionChange,
        left: Math.floor(this.lastWindowX),
        right: Math.floor(this.lastWindowX + this.lastWidth),
        top: Math.floor(this.lastWindowY),
        bottom: Math.floor(this.lastWindowY + this.lastHeight),
      },
    };
  }

  /**
   * Update function to be looped by `requestAnimationFrame`
   *
   * @returns {(boolean|undefined)} May exit early
   */
  update() {
    const { currWidth, currHeight, currMouseX, currMouseY } = this;
    if (this.updating) return false;

    // Reset the flags
    this.scrollChange =
      this.sizeChange =
      this.mouseChange =
      this.positionChange =
        false;

    // Check window X position
    if (window.screenX !== this.lastWindowX) {
      this.positionChange = true;
      this.lastWindowX = window.screenX;
    }

    // Check window Y position
    if (window.screenY !== this.lastWindowY) {
      this.positionChange = true;
      this.lastWindowY = window.screenY;
    }

    // Check scroll X
    if (window.pageXOffset !== this.lastX) {
      this.scrollChange = true;
      this.lastX = window.pageXOffset;
    }

    // Check scroll Y
    if (window.pageYOffset !== this.lastY) {
      this.scrollChange = true;
      this.lastY = window.pageYOffset;
    }

    // Check width
    if (currWidth !== this.lastWidth) {
      this.lastWidth = currWidth;
      this.scrollHeight = document.body.scrollHeight;
      this.sizeChange = true;
    }

    // Check height
    if (currHeight !== this.lastHeight) {
      this.lastHeight = currHeight;
      this.sizeChange = true;
    }

    // Check mouse X
    if (currMouseX !== this.lastMouseX) {
      this.lastMouseX = currMouseX;
      this.mouseChange = true;
    }

    // Check mouse y
    if (currMouseY !== this.lastMouseY) {
      this.lastMouseY = currMouseY;
      this.mouseChange = true;
    }

    // Finally, we can invoke the callbacks, but if something has changed
    if (
      this.scrollChange ||
      this.sizeChange ||
      this.mouseChange ||
      this.positionChange
    ) {
      // Pass the formatted data into each watched function
      this.callbacks.forEach((cb) => cb(this.formatData()));
    }

    // Reset and loop this method
    this.updating = false;
    requestAnimationFrame(this.update);
  }

  /**
   * Subscribes a function to the "watched functions" list.
   * Watched functions will be automatically called on update
   *
   * @param {Function} callback The function to call on update
   * @param {boolean} callOnWatch Call the function on subscribe? defaults to true
   */
  watch(callback, callOnWatch = true) {
    if (typeof callback !== "function") {
      throw new Error("Value passed to `watch` is not a function");
    }

    if (callOnWatch) {
      // Get a copy of the store
      const firstRunData = this.formatData();

      // Most watch functions will have guard clauses that check for change
      // To cicumvent this, we simulate that all values have changed on first run
      firstRunData.scroll.changed = true;
      firstRunData.mouse.changed = true;
      firstRunData.size.changed = true;
      firstRunData.position.changed = true;

      // Run the callback using the simulated data
      callback(firstRunData);
    }

    // Push the callback to the queue to ensure it runs on future updates
    this.callbacks.push(callback);
  }

  /**
   * Unsubscribe a function from the 'watched functions' list
   *
   * @param {Function} callback The function to be removed
   */
  unwatch(callback) {
    if (typeof callback !== "function") {
      throw new Error("Value passed to `unwatch` is not a function");
    }

    // Remove the callback from the list
    this.callbacks = this.callbacks.filter((cb) => cb !== callback);
  }
}

// Create a singleton instance of Tornis
const tornis = new Tornis();

// Export the Tornis API functions
export default () => ({
  watchViewport: tornis.watch,
  unwatchViewport: tornis.unwatch,
  getViewportState: tornis.formatData,
});

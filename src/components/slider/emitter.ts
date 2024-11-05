export class Emitter {
  #listeners = new Map<string, Set<(...args: any[]) => void>>();
  on = (event: string, cb: (...args: any[]) => void) => {
    if (this.#listeners.has(event)) {
      this.#listeners.get(event)!.add(cb);
    } else {
      this.#listeners.set(event, new Set([cb]));
    }
  };
  off = (event: string, cb: (...args: any[]) => void) => {
    if (this.#listeners.has(event)) {
      this.#listeners.get(event)!.delete(cb);
    }
  };
  emit = (event: string, ...rest: any[]) => {
    if (this.#listeners.has(event)) {
      for (const cb of this.#listeners.get(event)!) cb(...rest);
    }
  };
  clean() {
    this.#listeners.clear();
  }
}

export type ResizeObserverCallback = (
  entry: ResizeObserverEntry,
  observer: ResizeObserver,
) => void;

let _resizeObserver: ReturnType<typeof createResizeObserver>;

export function getResizeObserver() {
  return (_resizeObserver ??= createResizeObserver());
}

function createResizeObserver() {
  let ticking = false;
  let allEntries: ResizeObserverEntry[] = [];

  const callbacks = new Map<Element, ResizeObserverCallback[]>();

  const observer = new ResizeObserver(
    (entries: ResizeObserverEntry[], obs: ResizeObserver) => {
      allEntries = allEntries.concat(entries);

      if (!ticking) {
        requestAnimationFrame(() => {
          const triggered = new Set<Element>();
          for (let i = 0; i < allEntries.length; i++) {
            if (triggered.has(allEntries[i].target)) {
              continue;
            }

            triggered.add(allEntries[i].target);
            const cbs = callbacks.get(allEntries[i].target);
            cbs?.forEach((cb) => cb(allEntries[i], obs));
          }
          allEntries = [];
          ticking = false;
        });
      }

      ticking = true;
    },
  );

  return {
    observer,
    subscribe(target: Element, callback: ResizeObserverCallback) {
      observer.observe(target);
      const cbs = callbacks.get(target) ?? [];
      cbs.push(callback);
      callbacks.set(target, cbs);
    },
    unsubscribe(target: Element, callback: ResizeObserverCallback) {
      const cbs = callbacks.get(target) ?? [];
      if (cbs.length === 1) {
        observer.unobserve(target);
        callbacks.delete(target);
        return;
      }
      const cbIndex = cbs.indexOf(callback);
      if (cbIndex !== -1) {
        cbs.splice(cbIndex, 1);
      }
      callbacks.set(target, cbs);
    },
  };
}

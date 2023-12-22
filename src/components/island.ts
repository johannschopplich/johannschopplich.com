// Forked from: https://github.com/11ty/is-land
class Island extends HTMLElement {
  static tagName = "is-land";
  static prefix = "is-land--";
  static attr = {
    template: "data-island",
    ready: "ready",
    defer: "defer-hydration",
  };

  static onceCache = new Map<string, boolean>();
  static onReady = new Map<string, (island: typeof Island) => void>();

  static fallback = {
    ":not(is-land,:defined,[defer-hydration])": (
      readyPromise: Promise<void>,
      node: HTMLElement,
      prefix: string,
    ) => {
      // remove from document to prevent web component init
      const cloned = document.createElement(prefix + node.localName);
      for (const attr of node.getAttributeNames()) {
        cloned.setAttribute(attr, node.getAttribute(attr)!);
      }

      // Declarative Shadow DOM (with polyfill)
      let shadowroot = node.shadowRoot;
      if (!shadowroot) {
        const tmpl = node.querySelector<HTMLTemplateElement>(
          ":scope > template:is([shadowrootmode], [shadowroot])",
        );
        if (tmpl) {
          const mode = (tmpl.getAttribute("shadowrootmode") ||
            tmpl.getAttribute("shadowroot") ||
            "closed") as ShadowRootMode;
          shadowroot = node.attachShadow({ mode }); // default is closed
          shadowroot.appendChild(tmpl.content.cloneNode(true));
        }
      }

      // Cheers to https://gist.github.com/developit/45c85e9be01e8c3f1a0ec073d600d01e
      if (shadowroot) {
        cloned
          .attachShadow({ mode: shadowroot.mode })
          .append(...shadowroot.childNodes);
      }

      // Keep *same* child nodes to preserve state of children (e.g. details->summary)
      cloned.append(...node.childNodes);
      node.replaceWith(cloned);

      return readyPromise.then(() => {
        // Restore original children and shadow DOM
        if (cloned.shadowRoot) {
          node.shadowRoot?.append(...cloned.shadowRoot.childNodes);
        }
        node.append(...cloned.childNodes);
        cloned.replaceWith(node);
      });
    },
  };

  ready: Promise<void>;
  readyResolve!: () => void;

  constructor() {
    super();

    // Internal promises
    this.ready = new Promise((resolve) => {
      this.readyResolve = resolve;
    });
  }

  // any parents of `el` that are <is-land> (with conditions)
  static getParents(el: HTMLElement, stopAt: HTMLElement | boolean = false) {
    const nodes = [];
    while (el) {
      if (el.matches && el.matches(Island.tagName)) {
        if (stopAt && el === stopAt) {
          break;
        }

        if (Conditions.hasConditions(el)) {
          nodes.push(el);
        }
      }

      el = el.parentNode as HTMLElement;
    }
    return nodes;
  }

  static async ready(el: HTMLElement, parents?: HTMLElement[]) {
    if (!parents) {
      parents = Island.getParents(el);
    }
    if (parents.length === 0) {
      return;
    }
    const imports = await Promise.all(parents.map((p) => (p as Island).wait()));
    // return innermost module import
    if (imports.length) {
      return imports[0];
    }
  }

  forceFallback() {
    if ((window as any).Island) {
      Object.assign(Island.fallback, (window as any).Island.fallback);
    }

    for (const selector in Island.fallback) {
      // Reverse here as a cheap way to get the deepest nodes first
      const components = Array.from(
        this.querySelectorAll<HTMLElement>(selector),
      ).reverse();

      // with thanks to https://gist.github.com/cowboy/938767
      for (const node of components) {
        if (!node.isConnected) {
          continue;
        }

        const parents = Island.getParents(node);
        // must be in a leaf island (not nested deep)
        if (parents.length === 1) {
          const p = Island.ready(node, parents);
          Island.fallback[selector as keyof typeof Island.fallback](
            p,
            node,
            Island.prefix,
          );
        }
      }
    }
  }

  wait() {
    return this.ready;
  }

  async connectedCallback() {
    // Only use fallback content with loading conditions
    if (Conditions.hasConditions(this)) {
      // Keep fallback content without initializing the components
      this.forceFallback();
    }

    await this.hydrate();
  }

  getTemplates() {
    return this.querySelectorAll<HTMLTemplateElement>(
      `template[${Island.attr.template}]`,
    );
  }

  replaceTemplates(templates: NodeListOf<HTMLTemplateElement>) {
    // replace <template> with the live content
    for (const node of templates) {
      // if the template is nested inside another child <is-land> inside, skip
      if (Island.getParents(node, this).length > 0) {
        continue;
      }

      const value = node.getAttribute(Island.attr.template);
      // get rid of the rest of the content on the island
      if (value === "replace") {
        const children = Array.from(this.childNodes);
        for (const child of children) {
          this.removeChild(child);
        }
        this.appendChild(node.content);
        break;
      } else {
        const html = node.innerHTML;
        if (value === "once" && html) {
          if (Island.onceCache.has(html)) {
            node.remove();
            return;
          }

          Island.onceCache.set(html, true);
        }

        node.replaceWith(node.content);
      }
    }
  }

  async hydrate() {
    const conditions = [];
    if (this.parentNode) {
      // wait for all parents before hydrating
      conditions.push(Island.ready(this.parentNode as HTMLElement));
    }

    const attrs = Conditions.getConditions(this);
    for (const condition in attrs) {
      const _condition = condition as keyof typeof Conditions.map;
      if (Conditions.map[_condition]) {
        conditions.push(Conditions.map[_condition](attrs[_condition], this));
      }
    }

    // Loading conditions must finish before dependencies are loaded
    await Promise.all(conditions);

    this.replaceTemplates(this.getTemplates());

    for (const fn of Island.onReady.values()) {
      await fn.call(this, Island);
    }

    this.readyResolve();

    this.setAttribute(Island.attr.ready, "");

    // Remove [defer-hydration]
    for (const node of this.querySelectorAll<HTMLElement>(
      `[${Island.attr.defer}]`,
    )) {
      node.removeAttribute(Island.attr.defer);
    }
  }
}

class Conditions {
  static map = {
    visible: Conditions.visible,
    idle: Conditions.idle,
    interaction: Conditions.interaction,
    media: Conditions.media,
    "save-data": Conditions.saveData,
  };

  static hasConditions(node: HTMLElement) {
    return Object.keys(Conditions.getConditions(node)).length > 0;
  }

  static getConditions(node: HTMLElement) {
    const map: Record<string, string> = {};

    for (const key of Object.keys(Conditions.map)) {
      if (node.hasAttribute(`on:${key}`)) {
        map[key] = node.getAttribute(`on:${key}`)!;
      }
    }

    return map;
  }

  static visible(noop: unknown, el: HTMLElement) {
    if (!("IntersectionObserver" in window)) {
      // runs immediately
      return;
    }

    return new Promise<void>((resolve) => {
      const observer = new IntersectionObserver((entries) => {
        const [entry] = entries;
        if (entry.isIntersecting) {
          observer.unobserve(entry.target);
          resolve();
        }
      });

      observer.observe(el);
    });
  }

  // Warning: on:idle is not very useful with other conditions as it may resolve long before.
  static idle() {
    const onload = new Promise<void>((resolve) => {
      if (document.readyState !== "complete") {
        window.addEventListener("load", () => resolve(), { once: true });
      } else {
        resolve();
      }
    });

    if (!("requestIdleCallback" in window)) {
      // run immediately
      return onload;
    }

    // both idle and onload
    return Promise.all([
      new Promise<void>((resolve) => {
        requestIdleCallback(() => {
          resolve();
        });
      }),
      onload,
    ]);
  }

  static interaction(eventOverrides: string, el: HTMLElement) {
    let events = ["click", "touchstart"];
    // event overrides e.g. on:interaction="mouseenter"
    if (eventOverrides) {
      events = (eventOverrides || "").split(",").map((entry) => entry.trim());
    }

    return new Promise<void>((resolve) => {
      function resolveFn() {
        resolve();

        // cleanup the other event handlers
        for (const name of events) {
          el.removeEventListener(name, resolveFn);
        }
      }

      for (const name of events) {
        el.addEventListener(name, resolveFn, { once: true });
      }
    });
  }

  static media(query: string) {
    let mm = {
      matches: true,
    } as MediaQueryList;

    if (query && "matchMedia" in window) {
      mm = window.matchMedia(query);
    }

    if (mm.matches) {
      return;
    }

    return new Promise<void>((resolve) => {
      mm.addEventListener("change", (e) => {
        if (e.matches) {
          resolve();
        }
      });
    });
  }

  static saveData(expects: string) {
    // return early if API does not exist
    if (
      !("connection" in navigator) ||
      (navigator.connection as any).saveData === (expects !== "false")
    ) {
      return;
    }

    // dangly promise
    return new Promise(() => {});
  }
}

export { Island };

export function setup() {
  customElements.define(Island.tagName, Island);
}

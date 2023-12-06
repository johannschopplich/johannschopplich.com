const QUEUE_LIMIT = 5;

export default {
  methods: {
    async recursiveTranslateContent(
      obj,
      { targetLanguage = "en-US", translatableBlocks = [] },
    ) {
      const tasks = [];

      const processInBatches = async (tasks, limit) => {
        for (let i = 0; i < tasks.length; i += limit) {
          const batch = tasks.slice(i, i + limit);
          await Promise.all(batch.map((task) => task()));
        }
      };

      const createTranslationTask = (key) => async () => {
        const response = await this.$api.post("deepl/translate", {
          text: obj[key],
          targetLanguage,
        });
        obj[key] = response.result.text;
      };

      for (const key in obj) {
        // Handle simple strings
        if (typeof obj[key] === "string") {
          tasks.push(createTranslationTask(key));
        }
        // Detect and handle Kirby block fields
        else if (Array.isArray(obj[key])) {
          for (const block of obj[key]) {
            if (
              typeof block.content !== "object" ||
              block.content === null ||
              !block.id ||
              block.isHidden
            )
              continue;

            if (!Object.keys(translatableBlocks).includes(block.type)) continue;

            for (const blockFieldKey of Object.keys(block.content)) {
              if (!translatableBlocks[block.type].includes(blockFieldKey))
                continue;

              tasks.push(async () => {
                const response = await this.$api.post("deepl/translate", {
                  text: block.content[blockFieldKey],
                  targetLanguage,
                });
                block.content[blockFieldKey] = response.result.text;
              });
            }
          }
        }
        // Recursively process nested objects
        else if (typeof obj[key] === "object" && obj[key] !== null) {
          obj[key] = await this.recursiveTranslateContent(obj[key], {
            targetLanguage,
            translatableBlocks,
          });
        }
      }

      // Process translation tasks in batches
      await processInBatches(tasks, QUEUE_LIMIT);

      return obj;
    },
  },
};

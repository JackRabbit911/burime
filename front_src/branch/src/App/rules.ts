import type { Rules } from "./types";

export const titleRules: Rules = {
    required: 'Required',
    pattern: {
        value: /^(\S*\s){0,7}\S*$/gmi,
        message: 'Не больше 8 слов',
    },
}

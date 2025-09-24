import type { Rules } from "./types";

export const titleRules: Rules = {
    required: 'Пожалуйста, заполните поле',
    maxLength: {
        value: 120,
        message: 'Не больше 120 символов!!!',
    },
}

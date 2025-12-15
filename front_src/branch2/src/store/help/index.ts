import { createEvent, createStore, sample } from "effector";

import { globalReset } from "store/common";
import { messageAdded } from "store/messages";

import { getHelpDataFx } from "./fx";

import type { Help } from "./types";

// Events
export const helpRequested = createEvent<number>();

// Store
export const $hepls = createStore<Help[]>([])
    .reset(globalReset);

// Business logic

// запросить markdown текст помощи
sample({
    clock: helpRequested,
    target: getHelpDataFx,
});

// Успешно, получен markdown текст помощи
sample({
    clock: getHelpDataFx.done,
    source: $hepls,
    filter: (_, response) => Boolean(response?.result?.data?.success),
    fn: (helps, response) => [
        ...helps,
        {
            step: response.params,
            body: response?.result?.data?.result || '',
        },
    ],
    target: $hepls,
});

// НЕуспешно, Сообщение
sample({
    clock: getHelpDataFx.doneData,
    filter: (response) => !response?.data?.success,
    fn: (response) => ({
        title: 'Ошибка',
        description: response?.data?.error || '',
    }),
    target: messageAdded,
});

// Ошибка, Сообщение
sample({
    clock: getHelpDataFx.failData,
    fn: (error) => ({
        title: 'Ошибка',
        description: error?.message || '',
    }),
    target: messageAdded,
});

import { combine, createEvent, createStore } from "effector";
import { condition } from "patronum";

import { isValidMessage } from "./utils";

import type { Message } from "./types";
import { globalReset } from "store/common";

// Events
export const messageAdded = createEvent<Message>();
export const validMessageAdded = createEvent<Message>();
export const messageRemoved = createEvent();

// Store
const $messages = createStore<Message[]>([])
    .on(validMessageAdded, (store, payload) => [...store, payload])
    .on(messageRemoved, (store) => store.slice(1))
    .reset(globalReset);

// Readonly Store
export const $message = combine(
    $messages,
    (messages) => messages?.[0] || null,
);

// Business Logic
condition({
    source: messageAdded,
    if: (message) => isValidMessage(message),
    then: validMessageAdded,
    else: validMessageAdded.prepend(
        () => ({
            title: 'Отказ',
            description: 'Неверный формат сообщения',
        })
    ),
});

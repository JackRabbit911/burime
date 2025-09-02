import { createEvent, createStore } from "effector";

export const modalClicked = createEvent<boolean>()

export const $isOpen = createStore<boolean>(false)
    .on(modalClicked, (_, state) => state)

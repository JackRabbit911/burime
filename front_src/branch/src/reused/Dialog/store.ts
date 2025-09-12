import { createEvent, createStore } from "effector";

export const componentAdded = createEvent<React.ReactNode>()
export const componentRemoved = createEvent()
export const closeBtn = createEvent<boolean>()

const $components = createStore<React.ReactNode[]>([])
    .on(componentAdded, (store, data) => [...store, data])
    .on(componentRemoved, (store) => store.slice(1))

export const $component = $components.map(
    (store) => !store[0] ? null : store[0],
);

export const $closeBtn = createStore(true)
    .on(closeBtn, (_, closeBtn) => closeBtn)


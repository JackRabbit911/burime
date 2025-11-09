import { createEvent, createStore } from "effector";

export const modalOpened = createEvent<React.ReactNode>()
export const modalClosed = createEvent()
export const closeBtn = createEvent<boolean>()

export const $component = createStore<React.ReactNode | null>(null)
    .on(modalOpened, (_, data) => data)
    .reset(modalClosed)

export const $closeBtn = createStore(true)
    .on(closeBtn, (_, closeBtn) => closeBtn)

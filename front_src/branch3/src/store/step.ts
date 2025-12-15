import { createEvent, createStore } from "effector";

export const stepChanged = createEvent<number>()
export const globalReset = createEvent()

export const $step = createStore<number>(1)
    .on(stepChanged, (_, newStep) => newStep)
    .reset(globalReset)

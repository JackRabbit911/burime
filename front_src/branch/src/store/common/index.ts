import { createEvent, createStore } from "effector";

export const stepChanged = createEvent<number>()

export const $step = createStore(1).on(stepChanged, (_, newStep) => newStep)

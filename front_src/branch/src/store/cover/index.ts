import { combine, createEvent, createStore, sample } from "effector";
import { base64ToFile } from "./utils";
import { globalReset } from "store/common";
import type { Bootstrap } from "store/bootstrap/types";

// Events
export const coverFileChanged = createEvent<File>()
export const coverNameRecived = coverFileChanged
    .map(({ name }) => name)

export const coverFileCancelled = createEvent()
export const coverNameCancelled = coverFileCancelled
    .map(() => '')

export const bgFileChanged = createEvent<File>()
export const bgNameRecived = bgFileChanged
    .map(({ name }) => name)

export const bgFileCancelled = createEvent()
export const bgNameCancelled = bgFileCancelled
    .map(() => '')

export const coverFromBootstrap = createEvent<Bootstrap>()

// Stores
export const $coverFile = createStore<File | null>(null)
    .on(coverFileChanged, (_, data) => data)
    .reset(coverFileCancelled, globalReset)

export const $bgFile = createStore<File | null>(null)
    .on(bgFileChanged, (_, data) => data)
    .reset(bgFileCancelled, globalReset)

// Readonly Stores
export const $coverUrl = combine($coverFile, (coverFile) => {
    if (!coverFile) {
        return ''
    }

    return URL.createObjectURL(coverFile)
})


export const $bgUrl = combine($bgFile, (bgFile) => {
    if (!bgFile) {
        return ''
    }

    return URL.createObjectURL(bgFile)
})

sample({
    clock: coverFromBootstrap,
    filter: (result) => Boolean(result?.files?.bg_img),
    fn: (result) => base64ToFile(result.files.bg_img),
    target: $bgFile,
})

sample({
    clock: coverFromBootstrap,
    filter: (result) => Boolean(result?.files?.cover),
    fn: (result) => base64ToFile(result.files.cover),
    target: $coverFile,
})

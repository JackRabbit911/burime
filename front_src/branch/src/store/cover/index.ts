import { combine, createEvent, createStore, sample } from "effector";
import { getBootstrapFx } from "../bootstrap";
import { base64ToFile } from "./utils";

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

export const $coverFile = createStore<File | null>(null)
    .on(coverFileChanged, (_, data) => data)
    .reset(coverFileCancelled)

export const $coverUrl = combine($coverFile, (coverFile) => {
    if (!coverFile) {
        return ''
    }

    return URL.createObjectURL(coverFile)
})

export const $bgFile = createStore<File | null>(null)
    .on(bgFileChanged, (_, data) => data)
    .reset(bgFileCancelled)

export const $bgUrl = combine($bgFile, (bgFile) => {
    if (!bgFile) {
        return ''
    }

    return URL.createObjectURL(bgFile)
})

sample({
    clock: getBootstrapFx.doneData,
    filter: (response) => Boolean(response.result.files.bg_img),
    fn: (response) => base64ToFile(response.result.files.bg_img),
    target: $bgFile,
})

sample({
    clock: getBootstrapFx.doneData,
    filter: (response) => Boolean(response.result.files.cover),
    fn: (response) => base64ToFile(response.result.files.cover),
    target: $coverFile,
})

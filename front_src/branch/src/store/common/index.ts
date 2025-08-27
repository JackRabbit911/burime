import { combine, createEvent, createStore } from "effector";

export const stepChanged = createEvent<number>()
export const coverFileChanged = createEvent<File>()
export const coverNameRecived = coverFileChanged
    .map(({ name }) => name)

export const $step = createStore(1)
    .on(stepChanged, (_, newStep) => newStep)

export const $coverFile = createStore<File | null>(null)
    .on(coverFileChanged, (_, data) => data)

export const $coverUrl = combine($coverFile, (coverFile) => {
    if (!coverFile) {
        return ''
    }

    return URL.createObjectURL(coverFile)
})


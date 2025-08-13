import { createEvent, createStore } from "effector";

export const genreToggled = createEvent<number>()
export const $selectedGenres = createStore<number[]>([])
    .on(
        genreToggled,
        (genres, id) => genres.includes(id) ? genres.filter((genreId) => genreId !== id) : [...genres, id]
    )

export const rwModeToggled = createEvent<number>()
export const $selectedRWMode = createStore(0)
    .on(rwModeToggled, (_, id) => id)

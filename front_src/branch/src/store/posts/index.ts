import { createEvent, createStore, sample } from "effector";
import type { Posts } from "./types";
import { getBootstrapFx } from "../bootstrap";
import { globalReset } from "store/common";
import { debug } from "patronum";

export const firstPostChanged = createEvent<string>()
export const lastPostChanged = createEvent<string>()

export const $posts = createStore<Posts>({
    first: {id: null, body: ''},
    last: {id: null, body: ''},
}).on(getBootstrapFx.doneData, (_, data) => data.result.posts)
    .reset(globalReset)

sample({
    clock: firstPostChanged,
    source: $posts,
    fn: (posts, body) => ({
        ...posts,
        first: {...posts.first, body}
    }),
    target: $posts,
})

sample({
    clock: lastPostChanged,
    source: $posts,
    fn: (posts, body) => ({
        ...posts,
        last: {...posts.last, body}
    }),
    target: $posts,
})

debug($posts)

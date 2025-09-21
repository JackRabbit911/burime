import { createEvent, createStore, sample } from "effector";
import { debug } from "patronum";

import { globalReset } from "store/common";

import type { Posts } from "./types";
import type { Bootstrap } from "store/bootstrap/types";

// Events
export const firstPostChanged = createEvent<string>()
export const lastPostChanged = createEvent<string>()
export const postFromBootstrap = createEvent<Bootstrap>()

// Store
export const $posts = createStore<Posts>({
    first: {id: null, body: ''},
    last: {id: null, body: ''},
}).on(postFromBootstrap, (_, result) => result?.posts)
    .reset(globalReset)

// Business logic
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

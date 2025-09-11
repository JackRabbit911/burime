import { createEvent, createStore } from "effector";
import type { Posts } from "./types";
import { getBootstrapFx } from "../bootstrap";
import { globalReset } from "store/common";

export const firstPostChanged = createEvent<string>()
export const lastPostChanged = createEvent<string>()

export const $posts = createStore<Posts>({first: '', last: ''})
    .on(getBootstrapFx.doneData, (_, data) => data.result.posts)
    .on(firstPostChanged, (store, first) => ({...store, first}))
    .on(lastPostChanged, (store, last) => ({...store, last}))
    .reset(globalReset)

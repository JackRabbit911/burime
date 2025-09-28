import { createEffect, createEvent, restore, sample } from "effector";
import ajax from "services/ajax";
import type { ApiResponse } from "services/ajax/types";
import { $branch } from "store";
import type { Payload, SaveResponse } from "./types";
import { $posts } from "../posts";
import { $bgFile, $coverFile } from "../cover";

export const published = createEvent()
export const allRightChanged = createEvent<boolean>()

const sendFormDataFx = createEffect(
    (data: Payload) => ajax.postForm<ApiResponse<SaveResponse>>('branch/save', data)
)

export const $allRight = restore(allRightChanged, false)

sample({
    clock: published,
    source: {
        branch: $branch,
        posts: $posts,
        bg_img: $bgFile,
        cover: $coverFile,
    },
    target: sendFormDataFx,
})

sample({
    clock: sendFormDataFx.doneData,
    filter: (response) => Boolean(response?.data?.success),
    target: allRightChanged.prepend(() => true),
})

sample({
    clock: sendFormDataFx.doneData,
    source: $branch,
    filter: (_, response) => Boolean(response?.data?.success),
    fn: (branch, response) => ({
        ...branch,
        id: response.data.result.branch_id,
    }),
    target: $branch, 
})

sample({
    clock: sendFormDataFx.doneData,
    source: $posts,
    filter: (_, response) => Boolean(response?.data?.success),
    fn: (posts, response) => ({
        ...posts,
        first: {
            ...posts.first,
            id: response.data.result.first_id,
        },
        last: {
            ...posts.last,
            id: response.data.result.last_id,
        },
    }),
    target: $posts, 
})

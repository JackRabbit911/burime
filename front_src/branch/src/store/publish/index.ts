import { createEffect, createEvent, restore, sample } from "effector";
import ajax from "api/ajax";
import type { ApiResponse } from "api/types";
import { $branch } from "store";
import type { Payload } from "./types";
import { $posts } from "../posts";
import { $bgFile, $coverFile } from "../cover";

export const published = createEvent()
export const allRightChanged = createEvent<boolean>()

const sendFormDataFx = createEffect(
    (data: Payload) => ajax.postForm<ApiResponse<number>>('branch/save', data)
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
        id: response.data.result,
    }),
    target: $branch, 
})

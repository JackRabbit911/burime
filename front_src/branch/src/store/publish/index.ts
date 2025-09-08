import { createEffect, createEvent, sample } from "effector";
import ajax from "../../api/ajax";
import type { ApiResponse } from "../../api/types";
import { $branch } from "../branch";
import type { Payload } from "./types";
import { $posts } from "../posts";
import { $bgFile, $coverFile } from "../common";
// import { debug } from "patronum";

export const published = createEvent()

const sendFormDataFx = createEffect(
    (data: Payload) => ajax.postForm<ApiResponse<Payload>>('branch/save', data)
)

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

// const r = sample({
//     clock: sendFormDataFx.doneData,
// })

// debug({r})

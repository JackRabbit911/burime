import { createEffect, createEvent, sample } from "effector";
import ajax from "../../api/ajax";
import type { ApiResponse } from "../../api/types";
import { $branch } from "../branch";
import type { Branch } from "../bootstrap/types";
// import { debug } from "patronum";

export const published = createEvent()

const sendFornDataFx = createEffect(
    (branch: Branch) => {
        return ajax.postForm<ApiResponse<Branch>>('branch/save', branch)
    }
)

sample({
    clock: published,
    source: $branch,
    target: sendFornDataFx,
})

// const r = sample({
//     clock: sendFornDataFx.doneData,
// })

// debug({r})

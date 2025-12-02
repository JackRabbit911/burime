import { createEffect, createEvent, sample } from "effector";
import type { FormData } from "schema/output";
import ajax from "services/ajax";
import type { ApiResponse } from "services/ajax/types";
import * as z from "zod"

const uri = '/branch/create/save'

const publishSch = z.array(z.string())
type Publish = z.infer<typeof publishSch>

export const published = createEvent<FormData>()
export const draftClicked = createEvent<FormData>()

export const publishFx = createEffect(
    ( data: FormData ) => (
        ajax.postForm<ApiResponse<Publish>>(uri, data)
    )
)

export const draftFx = createEffect(
    ( data: FormData ) => (
        ajax.postForm<ApiResponse<Publish>>(uri, data, {params: {draft: true}})
    )
)

sample({
    clock: published,
    target: publishFx,
})

sample({
    clock: draftClicked,
    target: draftFx,
})

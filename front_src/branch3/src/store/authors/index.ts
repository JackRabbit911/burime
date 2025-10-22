import { createEffect, createEvent, createStore } from "effector"
import type { Authors, AuthorsPayload } from "schema/authors"
import ajax from "services/ajax"
import type { ApiResponse } from "services/ajax/types"
import { globalReset } from "store/step"

export const authorsFilterChanged = createEvent<string>()

export const getAuthorsFx = createEffect(
    (payload: AuthorsPayload) => ajax.get<ApiResponse<Authors>>(
        '/branch/create/authors', {
            params: payload,
        })
)

export const $authors = createStore<Authors | null>(null)
    .on(getAuthorsFx.doneData, (_, response) => response.data.result)
    .reset(globalReset)

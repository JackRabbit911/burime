import { combine, createEffect, createEvent, createStore, sample } from "effector"
import { authorsSch, type Authors, type AuthorsPayload } from "schema/authors"
import ajax from "services/ajax"
import type { ApiResponse } from "services/ajax/types"
import { $bootstrapStatus } from "store/bootstrap"
import { globalReset } from "store/step"

export const authorsPageChanged = createEvent<number>()
export const authorsLimitChanged = createEvent<number>()

export const getAuthorsFx = createEffect(
    (payload: AuthorsPayload) => ajax.get<ApiResponse<Authors>>(
        '/branch/create/getauthors', {
            params: payload,
        })
)

export const $authors = createStore<Authors | null>(null)
    .on(getAuthorsFx.doneData, (_, response) => response.data.result)
    .reset(globalReset)

export const $total = combine($authors, (authors) => authors?.count || 0)

sample({
    clock: getAuthorsFx.doneData,
    source: $authors,
    filter: (data) => {
        const valid = authorsSch.safeParse(data)

        if (Boolean(valid.error)) {
            console.log(valid.error)
        }
    
        return Boolean(valid.error)
    },
    fn: () => 555,
    target: $bootstrapStatus,
})

import { combine, createEffect, createEvent, createStore, sample } from "effector"
import { authorsSch, type Authors, type AuthorsPayload } from "schema/authors"
import ajax from "services/ajax"
import type { ApiResponse } from "services/ajax/types"
import { $bootstrapStatus } from "store/bootstrap"
import { globalReset } from "store/step"
import type { AxiosError, AxiosResponse } from "axios"
import { getAuthorsUri } from "constants"

export const authorsPageChanged = createEvent<number>()
export const authorsLimitChanged = createEvent<number>()

export const memberIdSetted = createEvent<number>()
export const memberIdResetted = createEvent()

export const getAuthorsFx = createEffect
<AuthorsPayload, AxiosResponse<ApiResponse<Authors>>, AxiosError>(
    (payload: AuthorsPayload) => ajax.get(
        getAuthorsUri, {
            params: payload,
        })
)

export const $authors = createStore<Authors | null>(null)
    .reset(globalReset)

export const $total = combine($authors, (authors) => authors?.count || 0)

export const $memberId = createStore<number>(0)
    .on(memberIdSetted, (_, id) => id)
    .reset(memberIdResetted)

sample({
    clock: getAuthorsFx.doneData,
    filter: (response) => !response?.data?.success,
    fn: (response) => {
        console.log(response?.data?.error)
        return 400
    },
    target: $bootstrapStatus,
})

sample({
    clock: getAuthorsFx.doneData,
    filter: (response) => {
        if (!response?.data?.success) {
            return false
        }

        const valid = authorsSch.safeParse(response?.data?.result)

        if (valid?.error) {
            console.log(valid.error)
        }
    
        return !valid.success
    },
    fn: () => 555,
    target: $bootstrapStatus,
})

sample({
    clock: getAuthorsFx.doneData,
    filter: (response) => response?.data?.success,
    fn: (response) => response?.data?.result,
    target: $authors,
})


import { createEffect, createStore } from "effector"
import type { Authors } from "schema/authors"
import ajax from "services/ajax"
import type { ApiResponse } from "services/ajax/types"

export const getAuthorsFx = createEffect(
    () => ajax.get<ApiResponse<Authors>>('/branch/create/authors')

    // async () => {
    //     const response = await ajax.get<ApiResponse<Authors>>('/branch/create/authors', {
    //         // params: payload
    //     })

    //     return response.data
    // }
)

export const $authors = createStore<Authors | null>(null)
    .on(getAuthorsFx.doneData, (_, response) => response.data.result)

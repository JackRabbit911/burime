import { combine, createEffect, createStore } from "effector"
import ajax from "../../api/ajax"
import type { ApiResponse } from "../../api/types"
import type { Vocabularies } from "./types"

export const getVocabulariesFx = createEffect(
    async () => {
        const response = await ajax.get<ApiResponse>('/branch/create/vocabularies')

        return response.data.result
    }
)

export const $vocabularies = createStore<Vocabularies | null>(null)
    .on(getVocabulariesFx.doneData, (_, data) => data)


export const $genresList = combine($vocabularies, (data) => data?.genres || [])

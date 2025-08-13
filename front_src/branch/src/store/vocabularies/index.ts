import { combine, createEffect, createStore } from "effector"
import ajax from "../../api/ajax"
import type { ApiResponse } from "../../api/types"
import type { Vocabularies } from "./types"
import { getSameWeightGenres } from "./utils"

export const getVocabulariesFx = createEffect(
    async () => {
        const response = await ajax.get<ApiResponse<Vocabularies>>('/branch/create/vocabularies')

        console.log(response.data.result)
        return response.data
    }
)

export const $vocabularies = createStore<Vocabularies | null>(null)
    .on(getVocabulariesFx.doneData, (_, data) => data.result)

export const $sameWeightGenres = combine($vocabularies, (data) => getSameWeightGenres(data?.genres || []))
export const $branch = combine($vocabularies, (data) => data?.branch || null)

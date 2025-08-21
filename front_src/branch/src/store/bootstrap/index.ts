import { createEffect, createStore } from "effector";
import ajax from "../../api/ajax";
import type { ApiResponse } from "../../api/types";
import type { Bootstrap, SameWeightGenres } from "./types";
import { getSameWeightGenres } from "./utils";

export const getBootstrapFx = createEffect(
    async () => {
        const response = await ajax.get<ApiResponse<Bootstrap>>('/branch/create/bootstrap')

        return response.data
    }
)

export const $sameWeightGenres = createStore<SameWeightGenres[]>([])
    .on(getBootstrapFx.doneData, (_, data) => getSameWeightGenres(data?.result?.genres || []))

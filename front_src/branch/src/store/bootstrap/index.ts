import { createEffect, createStore, sample } from "effector";
import ajax from "../../api/ajax";
import type { ApiResponse } from "../../api/types";
import type { Bootstrap, SameWeightGenres } from "./types";
import { getSameWeightGenres } from "./utils";
import { globalReset } from "store/common";
// import { debug } from "patronum";
import type { AxiosError } from "axios";

export const getBootstrapFx = createEffect<void, ApiResponse<Bootstrap>, AxiosError>(
    async () => {
        const path = window.location.pathname
        const last = path.split('/').pop()
        
        let uri = '/branch/create/bootstrap'
        
        if (!isNaN(Number(last))) (
            uri += '/' + last
        )

        const response = await ajax.get<ApiResponse<Bootstrap>>(uri)

        return response.data
    }
)

export const $sameWeightGenres = createStore<SameWeightGenres[]>([])
    .on(getBootstrapFx.doneData, (_, data) => getSameWeightGenres(data?.result?.genres || []))
    .reset(globalReset)

export const $bootstrapStatus = createStore(200)

sample({
    clock: getBootstrapFx.failData,
    fn: (response) => response.status!,
    target: $bootstrapStatus,
})


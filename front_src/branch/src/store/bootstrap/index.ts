import { createEffect, createStore, sample } from "effector";

import type { AxiosError } from "axios";

import ajax from "api/ajax";
import { globalReset } from "store/common";
import { bootstrapUri } from "./constants";
import { getSameWeightGenres } from "./utils";

import type { ApiResponse } from "api/types";
import type { Bootstrap, SameWeightGenres } from "./types";

// Side Effects
export const getBootstrapFx = createEffect<string, ApiResponse<Bootstrap>, AxiosError>(
    async (id: string) => {
        const url = [bootstrapUri, id].filter(Boolean).join('/')
        const response = await ajax.get<ApiResponse<Bootstrap>>(url)

        return response.data
    }
)

// Stores
export const $sameWeightGenres = createStore<SameWeightGenres[]>([])
    .on(getBootstrapFx.doneData, (_, data) => getSameWeightGenres(data?.result?.genres || []))
    .reset(globalReset)

export const $bootstrapStatus = createStore(200)

// Business Logic
sample({
    clock: getBootstrapFx.failData,
    fn: (response) => response.status!,
    target: $bootstrapStatus,
})


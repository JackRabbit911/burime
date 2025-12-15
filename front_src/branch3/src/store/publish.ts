import type { AxiosError, AxiosResponse } from "axios";
import { createEffect, createEvent, createStore, sample } from "effector";
import { modalOpened } from "reused/Modal/store";
import type { DraftData, FormData } from "schema/output";
import ajax from "services/ajax";
import type { ApiResponse } from "services/ajax/types";
import { globalReset } from "./step";
import { $bootstrapStatus } from "./bootstrap";
import { saveBranchUri, saveDraftUri } from "constants";

type FinalResponse = {
    [x: string]: string | number;
}

export const published = createEvent<FormData>()
export const draftClicked = createEvent<DraftData>()

export const publishFx = createEffect
    <FormData, AxiosResponse<ApiResponse<FinalResponse>>, AxiosError>(
        (data: FormData) => (
            ajax.postForm(saveBranchUri, data)
        )
    )

export const draftFx = createEffect
    <DraftData, AxiosResponse<ApiResponse<FinalResponse>>, AxiosError>(
        (data: DraftData) => (
            ajax.postForm(saveDraftUri, data)
        )
    )

export const $finalResponse = createStore<FinalResponse | null>(null)
    .reset(draftClicked, published, globalReset)

sample({
    clock: published,
    target: publishFx,
})

sample({
    clock: draftClicked,
    target: draftFx,
})

sample({
    clock: publishFx.failData,
    fn: (error) => error.message,
    target: modalOpened,
})

sample({
    clock: [publishFx.doneData, draftFx.doneData],
    filter: (response) => !response?.data?.success,
    fn: (response) => {
        console.log(response?.data?.error)
        return 400
    },
    target: $bootstrapStatus,
})

sample({
    clock: [publishFx.doneData, draftFx.doneData],
    filter: (response) => Boolean(response?.data?.success),
    fn: (response) => response.data.result,
    target: $finalResponse,
})

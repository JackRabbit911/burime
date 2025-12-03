import type { AxiosError, AxiosResponse } from "axios";
import { createEffect, createEvent, createStore, sample } from "effector";
import { modalOpened } from "reused/Modal/store";
import type { FormData } from "schema/output";
import ajax from "services/ajax";
import type { ApiResponse } from "services/ajax/types";

const uri = '/branch/create/save'

type FinalResponse = {
    [x: string]: string | number;
}

type FinalApiResponse = ApiResponse<FinalResponse> | null;

export const published = createEvent<FormData>()
export const draftClicked = createEvent<FormData>()

export const publishFx = createEffect
    <FormData, AxiosResponse<ApiResponse<FinalResponse>>, AxiosError>(
        (data: FormData) => (
            ajax.postForm(uri, data)
        )
    )

export const draftFx = createEffect
    <FormData, AxiosResponse<ApiResponse<FinalResponse>>, AxiosError>(
        (data: FormData) => (
            ajax.postForm(uri, data, { params: { draft: true } })
        )
    )

export const $finalResponse = createStore<FinalApiResponse>(null)

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
    clock: publishFx.doneData,
    fn: (response) => response.data,
    target: $finalResponse,
})

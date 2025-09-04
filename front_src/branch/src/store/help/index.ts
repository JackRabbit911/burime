import { createEffect, createEvent, createStore, sample } from "effector";
import ajax from "../../api/ajax";
import type { ApiResponse } from "../../api/types";
import { debug } from "patronum";
import type { Help } from "./types";

export const helpBtnClicked = createEvent<number>()

export const getHelpDataFx = createEffect(
    (step: number) => {
        const uri = '/branch/help/' + step.toString()

        return ajax.get<ApiResponse<string>>(uri)
    }
)

export const $hepls = createStore<Help[]>([])

sample({
    clock: helpBtnClicked,
    source: $hepls,
    filter: (helps, step) => !Boolean(helps.find((help) => help.step === step)),
    fn: (_, step) => step,
    target: getHelpDataFx,
})

sample({
    clock: getHelpDataFx.done,
    source: $hepls,
    filter: (_, response) => Boolean(response?.result?.data?.success),
    fn: (helps, response) => [...helps, {
        step: response.params,
        body: response?.result?.data?.result
    }],
    target: $hepls,
})

debug({ $hepls, getHelpDataFx, helpBtnClicked })

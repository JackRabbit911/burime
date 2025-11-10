import { createEffect, createEvent, createStore, sample } from "effector";
import ajax from "services/ajax";
import type { ApiResponse } from "services/ajax/types";
import { helpInputSch, type Help } from "./schema";
import { globalReset } from "store/step";
import { $bootstrapStatus } from "store/bootstrap";

export const helpBtnClicked = createEvent<number>()

export const getHelpDataFx = createEffect(
    (step: number) => {
        const uri = '/branch/help/' + step.toString()
        return ajax.get<ApiResponse<string>>(uri)
    }
)

export const $hepls = createStore<Help[]>([])
    .reset(globalReset)

sample({
    clock: helpBtnClicked,
    source: $hepls,
    filter: (helps, step) => !Boolean(helps.find((help) => help.step === step)),
    fn: (_, step) => step,
    target: getHelpDataFx,
})

sample({
    clock: getHelpDataFx.doneData,
    filter: (response) => {
        const valid = helpInputSch.safeParse(response?.data?.result)

        if (Boolean(valid.error)) {
            console.log(valid.error)
        }

        return Boolean(valid.error)
    },
    fn: () => 555,
    target: $bootstrapStatus,
})

sample({
    clock: getHelpDataFx.doneData,
    source: $hepls,
    filter: (_, response) => !response?.data?.success,
    fn: () => 400,
    target: $bootstrapStatus,
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

import { combine, createEffect, createEvent, createStore, sample } from "effector";
import type { AxiosError, AxiosResponse } from "axios";
import { z } from "zod";

import ajax from "services/ajax";
import type { ApiResponse } from "services/ajax/types";
import { bootstrapSch, type Bootstrap } from "schema/input";
import { globalReset } from "store/step";
import { getBootsrapUri } from "constants";

type AxiosApiResponse = AxiosResponse<ApiResponse<Bootstrap>>;

const idSch = z.coerce.number().positive().optional()
const isDraftSch = z.literal("draft").optional()

export const appStarted = createEvent()

const getBranchIdFx = createEffect(() => {
    const pathname = window.location.pathname;
    const uriSegments = pathname.split('/').filter((v) => Boolean(v))

    const id = uriSegments[2]
    const draft = uriSegments[3]

    const success = idSch.safeParse(id).success && isDraftSch.safeParse(draft).success
    const path = [id, draft].filter(Boolean).join('/')

    return { path, success }
})

const getBootstrapFx = createEffect<string, AxiosApiResponse, AxiosError>(
    (path: string) =>
        ajax.get<ApiResponse<Bootstrap>>(
            [getBootsrapUri, path].filter(Boolean).join('/'),
        ),
);

export const $bootstrap = createStore<Bootstrap | null>(null)
    .reset(globalReset)

export const $bootstrapStatus = createStore(200)
    .reset(globalReset)

export const $permissions = combine($bootstrap, (store) => store?.authorsPermissions || {})
export const $statusObj = combine($bootstrap, (store) => store?.authorsStatuses || {})

sample({
    clock: appStarted,
    target: getBranchIdFx,
})

sample({
    clock: getBranchIdFx.doneData,
    filter: ({ success }) => !success,
    fn: () => 404,
    target: $bootstrapStatus,
});

sample({
    clock: getBranchIdFx.doneData,
    filter: ({ success }) => success,
    fn: ({ path }) => path,
    target: getBootstrapFx,
})

sample({
    clock: getBootstrapFx.doneData,
    filter: (response) => {
        const valid = bootstrapSch.safeParse(response?.data?.result)

        if (Boolean(valid.error)) {
            console.log(valid.error)
        }

        return Boolean(valid.error)
    },
    fn: () => 555,
    target: $bootstrapStatus,
})

sample({
    clock: getBootstrapFx.doneData,
    filter: (response) => Boolean(response?.data?.success),
    fn: (response) => response.data.result,
    target: $bootstrap,
});

sample({
    clock: getBootstrapFx.doneData,
    filter: (response) => !response?.data?.success,
    fn: () => 400,
    target: $bootstrapStatus,
});

sample({
    clock: getBootstrapFx.failData,
    fn: (error) => {
        return error?.status || 503
    },
    target: $bootstrapStatus,
});

import { createEffect, createEvent, createStore, sample } from "effector";
import type { AxiosError, AxiosResponse } from "axios";
import { z } from "zod";

import ajax from "services/ajax";
import type { ApiResponse } from "services/ajax/types";
import type { Bootstrap } from "schema/input";

type AxiosApiResponse = AxiosResponse<ApiResponse<Bootstrap>>;

const bootstrapUri = '/branch/create/getbootstrap';
const firstSegsRegular = /\/create\/branch\/?/g;
const idSch = z.preprocess(
    (val) => (val === "" ? null : val), // Convert empty string to null
    z.coerce.number().nullable() // Then coerce to number, allowing null
);

export const appStarted = createEvent()

const getBranchIdFx = createEffect(() => {
    const pathname = window.location.pathname;
    const id = pathname.replace(firstSegsRegular, '');
    const success = idSch.safeParse(id).success;

    return { id, success }
})

const getBootstrapFx = createEffect<string, AxiosApiResponse, AxiosError>(
    (id: string) =>
        ajax.get<ApiResponse<Bootstrap>>(
            [bootstrapUri, id].filter(Boolean).join('/'),
        ),
);

export const $bootstrap = createStore<Bootstrap | null>(null)
export const $bootstrapStatus = createStore(200)

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
    fn: ({ id }) => id,
    target: getBootstrapFx,
})

sample({
    clock: getBootstrapFx.doneData,
    filter: (response) => Boolean(response?.data?.success),
    fn: (response) => response.data.result,
    target: $bootstrap,
});

import { createEffect, createEvent, sample } from "effector";

import type { AxiosError, AxiosResponse } from "axios";

import { getIdWithValidation } from "./utils";
import ajax from "api/ajax";
import { bootstrapUri } from "./constants";
import { $bootstrapStatus, genresFromBootstrap } from "store/bootstrap";
import { postFromBootstrap } from "store/posts";
import { coverFromBootstrap } from "store/cover";
import { branchFromBootstrap } from "store/branch";

import type { Bootstrap } from "store/bootstrap/types";
import type { ApiResponse } from "api/types";

// Events
export const appStarted = createEvent()

// Side Effects
const getIdWithValidationFx = createEffect(getIdWithValidation);

const getBootstrapFx = createEffect<string, AxiosResponse<ApiResponse<Bootstrap>>, AxiosError>(
    (id: string) =>
        ajax.get<ApiResponse<Bootstrap>>(
            [bootstrapUri, id].filter(Boolean).join('/')
        )
)

// Business Logic

// Стартуем проверку
sample({
    clock: appStarted,
    target: getIdWithValidationFx,
})

// Если хреновый id в адресе /create/branch/:id
// Показать 404 и стоп
sample({
    clock: getIdWithValidationFx.doneData,
    filter: ({ success }) => !success,
    fn: () => 404,
    target: $bootstrapStatus,
});

// Если нормальный id (или его нет) в адресе /create/branch/:id
// запросить данные Бранча
sample({
    clock: getIdWithValidationFx.doneData,
    filter: ({ success }) => success,
    fn: ({ id }) => id,
    target: getBootstrapFx,
});

// 200 и !success
sample({
    clock: getBootstrapFx.doneData,
    filter: (response) => !response?.data?.success,
    fn: () => 500,
    target: $bootstrapStatus,
});

// Здесь только обработка статусов 299-402, 404-
// 401 перехватывается на уровне сервиса ajax
sample({
    clock: getBootstrapFx.failData,
    fn: (error) => error?.status || 503,
    target: $bootstrapStatus,
});

// Финал: данные успешно пришли - раздать по сторам
sample({
    clock: getBootstrapFx.doneData,
    filter: (response) => Boolean(response?.data?.success),
    fn: (response) => response.data.result,
    target: [
        genresFromBootstrap,
        postFromBootstrap,
        coverFromBootstrap,
        branchFromBootstrap,
    ],
});

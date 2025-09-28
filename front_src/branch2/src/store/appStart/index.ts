import { createEvent, sample } from "effector";

import { $bootstrapStatus, genresFromBootstrap } from "store/bootstrap";
import { postFromBootstrap } from "store/posts";
import { coverFromBootstrap } from "store/cover";
import { branchFromBootstrap } from "store/branch";
import { getBootstrapFx, getIdWithValidationFx } from "./fx";

// Events
export const appStarted = createEvent();

// Business Logic

// Стартуем проверку
sample({
    clock: appStarted,
    target: getIdWithValidationFx,
});

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

// Ошибки (Исключение: 401 перехватывается сервисом ajax)
sample({
    clock: getBootstrapFx.failData,
    fn: (error) => error?.status || 503,
    target: $bootstrapStatus,
});

// Данные получены успешно, разложить по сторам
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

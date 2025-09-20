import { createEffect, createEvent, sample } from "effector";
import { getIdWithValidation } from "./utils";
import { $bootstrapStatus, getBootstrapFx } from "store/bootstrap";

export const appStarted = createEvent()

const getIdWithValidationFx = createEffect(getIdWithValidation);

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

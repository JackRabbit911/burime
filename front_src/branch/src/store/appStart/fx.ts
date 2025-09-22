import { createEffect } from "effector";

import type { AxiosError, AxiosResponse } from "axios";

import ajax from "api/ajax";

import type { ApiResponse } from "api/types";
import type { Bootstrap } from "store/bootstrap/types";

const idRegular = /^(1|2|3|4|5|6|7|8|9)\d{0,9}$/gm
const firstSegsRegular = /\/create\/branch\/?/g
const bootstrapUri = '/branch/create/bootstrap'
type AxiosApiResponse = AxiosResponse<ApiResponse<Bootstrap>>

// Side Effects
export const getIdWithValidationFx = createEffect(
    () => {
        const pathname = window.location.pathname
        const id = pathname.replace(firstSegsRegular, '')
        const success = Boolean(!id || idRegular.test(id))
    
        return { id, success }
    }
);

export const getBootstrapFx = createEffect<string, AxiosApiResponse, AxiosError>(
    (id: string) =>
        ajax.get<ApiResponse<Bootstrap>>(
            [bootstrapUri, id].filter(Boolean).join('/'),
        )
)

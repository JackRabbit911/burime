import { createEffect } from "effector";

import ajax from "services/ajax";

import type { ApiResponse } from "services/ajax/types";

// Side effects
export const getHelpDataFx = createEffect(
    (step: number) =>
        ajax.get<ApiResponse<string>>(
            `/branch/help/${step}`
        )
)

import { createEffect, createEvent, createStore } from "effector";
import ajax from "../../api/ajax";
import type { ApiResponse } from "../../api/types";
import type { Author, Authors } from "./types";

export const masterSelected = createEvent<string>()

export const getAuthorsFx = createEffect(
    async () => {
        const response = await ajax.get<ApiResponse<Authors>>('/branch/create/authors')

        console.log(response.data.result)
        return response.data
    }
)

export const $authors = createStore<Author[]>([])
    .on(getAuthorsFx.doneData, (_, data) => data.result.authors)

export const $ownAuthors = createStore<Author[]>([])
    .on(getAuthorsFx.doneData, (_, data) => data.result.ownAuthors)

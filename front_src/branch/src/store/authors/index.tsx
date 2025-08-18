import { createEffect, createEvent, createStore } from "effector";
import ajax from "../../api/ajax";
import type { ApiResponse } from "../../api/types";
import type { Author, Authors, BranchAuthor } from "./types";

export const masterIdSelected = createEvent<string>()
export const authorInvited = createEvent<Author>()
export const authorRemoved = createEvent<BranchAuthor>()
export const masterSelected = createEvent<Author | undefined>()
export const authorRoleToggled = createEvent<BranchAuthor>()

export const getAuthorsFx = createEffect(
    async () => {
        const response = await ajax.get<ApiResponse<Authors>>('/branch/create/authors')

        return response.data
    }
)

export const $authors = createStore<Author[]>([])
    .on(getAuthorsFx.doneData, (_, data) => data.result.authors)

export const $ownAuthors = createStore<Author[]>([])
    .on(getAuthorsFx.doneData, (_, data) => data.result.ownAuthors)

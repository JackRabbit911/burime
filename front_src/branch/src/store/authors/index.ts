import { combine, createEffect, createEvent, createStore, sample } from "effector";
import ajax from "../../api/ajax";
import type { ApiResponse } from "../../api/types";
import type { Author, Authors, AuthorsPayload, BranchAuthor } from "./types";
import { debug } from "patronum";

export const masterIdSelected = createEvent<string>()
export const authorInvited = createEvent<Author>()
export const authorRemoved = createEvent<BranchAuthor>()
export const masterSelected = createEvent<Author | undefined>()
export const authorRoleToggled = createEvent<BranchAuthor>()
export const authorsFilterChanged = createEvent<string>()
export const authorSearchChanged = createEvent<string>()
export const authorSearchClicked = createEvent()

export const getAuthorsFx = createEffect(
    async (payload: AuthorsPayload) => {
        const response = await ajax.get<ApiResponse<Authors>>('/branch/create/authors', {
            params: payload
        })

        return response.data
    }
)

export const $authors = createStore<Author[]>([])
    .on(getAuthorsFx.doneData, (_, data) => data.result.authors)

export const $ownAuthors = createStore<Author[]>([])
    .on(getAuthorsFx.doneData, (_, data) => data.result.ownAuthors)

export const $authorsFilter = createStore('')
    .on(authorsFilterChanged, (_, filter) => filter)

export const $authorSearch = createStore('')
    .on(authorSearchChanged, (_, search) => search)

const $authorsPayload = combine(
    $authorsFilter, $authorSearch,
    (authorsFilter, authorSearch) => ({
        filter: authorsFilter,
        search: authorSearch,
    })
)

sample({
    clock: [authorsFilterChanged, authorSearchClicked],
    source: $authorsPayload,
    target: getAuthorsFx,
})

debug($authorSearch)

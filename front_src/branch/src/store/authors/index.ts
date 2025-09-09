import { combine, createEffect, createEvent, createStore, sample } from "effector";
import ajax from "../../api/ajax";
import type { ApiResponse } from "../../api/types";
import type { Author, Authors, AuthorsPayload, BranchAuthor } from "./types";
import type { Pagination } from "../../reused/Paginator/types";
import { globalReset } from "store/common";

export const masterIdSelected = createEvent<string>()
export const authorInvited = createEvent<Author>()
export const authorRemoved = createEvent<BranchAuthor>()
export const masterSelected = createEvent<Author | undefined>()
export const authorRoleToggled = createEvent<BranchAuthor>()
export const authorsFilterChanged = createEvent<string>()
export const authorSearchChanged = createEvent<string>()
export const authorSearchClicked = createEvent()
export const authorsPageChanged = createEvent<number>()
export const authorsLimitChanged = createEvent<number>()

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
    .reset(globalReset)

export const $authorsCount = createStore(0)
    .on(getAuthorsFx.doneData, (_, data) => data.result.authorsCount)
    .reset(globalReset)

export const $ownAuthors = createStore<Author[]>([])
    .on(getAuthorsFx.doneData, (_, data) => data.result.ownAuthors)
    .reset(globalReset)

export const $authorsFilter = createStore('')
    .on(authorsFilterChanged, (_, filter) => filter)
    .reset(globalReset)

export const $authorSearch = createStore('')
    .on(authorSearchChanged, (_, search) => search)
    .reset(globalReset)

export const $authorsPagination = createStore<Pagination>({page: 1, limit: 25})
    .on(authorsPageChanged, (store, page) => ({...store, page}))
    .on(authorsLimitChanged, (store, limit) => ({...store, page:1, limit}))
    .on(authorsFilterChanged, (store) => ({...store, page:1}))
    .reset(globalReset)

export const $authorsPayload = combine(
    $authorsFilter, $authorSearch, $authorsPagination,
    (authorsFilter, authorSearch, {page, limit}) => ({
        filter: authorsFilter,
        search: authorSearch,
        page: page,
        limit: limit,
    })
)

export const $ownAuthorsOptions = combine($ownAuthors, (ownAuthors) => (
    ownAuthors.map(
        ({ id, alias }) => ({
            value: id,
            name: alias,
        })
    )
))

sample({
    clock: [
        authorsFilterChanged,
        authorSearchClicked,
        authorsPageChanged,
        authorsLimitChanged,
    ],
    source: $authorsPayload,
    target: getAuthorsFx,
})

sample({
    clock: masterIdSelected,
    source: $ownAuthors,
    fn: (authors, id) => {
        const author = authors.find((author) => author.id.toString() === id)

        return author
    },
    target: masterSelected
})

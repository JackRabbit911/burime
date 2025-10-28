import { perPages } from "constants";
import type { AuthorsPayload, BranchAuthor, OwnAuthors } from "schema/authors";
import type { Bootstrap } from "schema/input";

export const getDefaults = (bootstrap: Bootstrap) => ({
    branchTitle: bootstrap.branch.title || '',
    genres: bootstrap.branch.genres,
    branchRole: bootstrap.branch.role,
    moderation: Boolean(bootstrap.branch.info.moderation),
    comments: Boolean(bootstrap.branch.info.allow_comments),
    signature: Boolean(bootstrap.branch.info.signature),
    ageLimit: bootstrap.branch.age_limit,
    postSize: bootstrap.branch.info.post_size,
    timeLimit: bootstrap.branch.info.time_limit,
    description: bootstrap.branch.info.description,
    rules: bootstrap.branch.info.rules,
    members: getBranchAuthors(bootstrap.branch.members),
    masterId: getMasterId(bootstrap.branch.members, bootstrap.ownAuthors),
    moderator: getModerators(bootstrap.branch.members),
    authorsPayload: setAuthorsPayload(),
})

export function setAuthorsPayload(limit = perPages[0]): AuthorsPayload {
    return {
        filter: null,
        search: null,
        page: 1,
        limit: limit,
    }
}

function getMasterId(authors: BranchAuthor[], ownAuthors: OwnAuthors) {
    const master = authors.find(
        ({ role }) => role >= 150
    )

    return !master ? ownAuthors[0].id : master.id
}

function getBranchAuthors(authors: BranchAuthor[]) {
    return authors.filter((author) => author.role < 150)
}

function getModerators(authors: BranchAuthor[]) {
    return authors.map((author) => {
        if (author.role === 100) {
            return author.id
        }
    }).filter((elem) => elem !== undefined)
}

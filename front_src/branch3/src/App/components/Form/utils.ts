import type { BranchAuthor, OwnAuthors } from "schema/authors";
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
    authors: bootstrap.branch.authors,
    masterId: getMasterId(bootstrap.branch.authors, bootstrap.ownAuthors),
    // ownAuthors: null,
})

export function getMasterId(authors: BranchAuthor[], ownAuthors: OwnAuthors) {
    const master = authors.find(
        ({ role }) => role >= 150
    )

    return !master ? ownAuthors[0].id : master.id
}

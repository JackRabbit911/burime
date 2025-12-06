import { perPages } from "constants";
import type { AuthorsPayload, Member, OwnAuthors } from "schema/authors";
import { base64ToFile } from "schema/files";
import type { Bootstrap } from "schema/input";

export const getDefaults = (bootstrap: Bootstrap) => {
    const masterId = getMasterId(bootstrap.branch.members, bootstrap.ownAuthors)
    
    return {
    branch: bootstrap.branch,
    posts: bootstrap.posts,
    masterId: masterId,
    cover: base64ToFile(bootstrap.files.cover, 'cover'),
    bgImg: base64ToFile(bootstrap.files.bg_img, 'background'),
    authorsPayload: setAuthorsPayload(),
}}

export function setAuthorsPayload(limit = perPages[0]): AuthorsPayload {
    return {
        filter: null,
        search: null,
        page: 1,
        limit: limit,
    }
}

function getMasterId(authors: Member[], ownAuthors: OwnAuthors) {
    const master = authors.length > 0
        ? authors.reduce((acc, val) => {
            if (acc.role < val.role) {
                acc = val
            }

            return acc
        })
        : null
    return !master ? ownAuthors[0].id : master.id
}

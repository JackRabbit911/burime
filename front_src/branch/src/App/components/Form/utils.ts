import { perPages } from "constants";
import type { AuthorsPayload, Member, OwnAuthors } from "schema/authors";
import { base64ToFile } from "schema/files";
import type { Bootstrap } from "schema/input";
import { changeMaster } from "../Authors/utils";

export const getDefaults = (bootstrap: Bootstrap) => {
    const masterId = getMasterId(bootstrap.members, bootstrap.ownAuthors)
    const members = getMembers(bootstrap.members, bootstrap.ownAuthors, masterId)

    return {
        branch: bootstrap.branch,
        branch_genres: bootstrap.branch_genres,
        members: members,
        posts: bootstrap.posts,
        masterId: masterId,
        cover: base64ToFile(bootstrap.files.cover, 'cover'),
        bgImg: base64ToFile(bootstrap.files.bg_img, 'background'),
        authorsPayload: setAuthorsPayload(),
        draft: bootstrap.draft || null,
    }
}

export function setAuthorsPayload(limit = perPages[0]): AuthorsPayload {
    return {
        filter: null,
        search: null,
        page: 1,
        limit: limit,
    }
}

function getMasterId(members: Member[], ownAuthors: OwnAuthors) {
    const master = members.length > 0
        ? members.reduce((acc, val) => {
            if (acc.role < val.role) {
                acc = val
            }

            return acc
        })
        : null
    return !master ? ownAuthors[0].id : master.id
}

function getMembers(members: Member[], ownAuthors: OwnAuthors, masterId: number) {
    return members.length === 0 ? changeMaster(members, ownAuthors, masterId) : members
}

export const getTitle = (branchId: number | null) => {
    const suffix = branchId ? 'editing' : 'creation'
    
    return 'Branch ' + suffix
}

import { perPages } from "constants";
import type { AuthorsPayload, Member, OwnAuthors } from "schema/authors";
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
    members: getMembers(bootstrap.branch.members),
    masterId: getMasterId(bootstrap.branch.members, bootstrap.ownAuthors),
    moderator: getModerators(bootstrap.branch.members),
    bg_color: bootstrap.branch.info.bg_color,
    text_size: bootstrap.branch.info.text_size,
    text_color: bootstrap.branch.info.text_color,
    cover: base64ToFile(bootstrap.files.cover, 'cover'),
    bgImg: base64ToFile(bootstrap.files.bg_img, 'background'),
    authorsPayload: setAuthorsPayload(),
    firstPost: bootstrap.posts.first.body || '',
    lastPost: bootstrap.posts.last.body || '',
})

export function setAuthorsPayload(limit = perPages[0]): AuthorsPayload {
    return {
        filter: null,
        search: null,
        page: 1,
        limit: limit,
    }
}

function getMasterId(authors: Member[], ownAuthors: OwnAuthors) {
    const master = authors.find(
        ({ role }) => role >= 150
    )

    return !master ? ownAuthors[0].id : master.id
}

function getMembers(members: Member[]) {
    return members.filter((member) => member.role < 150)
}

function getModerators(members: Member[]) {
    return members.map((member) => {
        if (member.role === 100) {
            return member.id
        }
    }).filter((elem) => elem !== undefined)
}

function base64ToFile(data: string | null, filename: string) {
    if (!data) {
        return null
    }

    const arr = data.split(',')
    const f = arr[0].match(/:(.*?);/)
    const mime = f ? f[0] : ''
    const ext = mime.split('/')[1].replace(/;/, '')
    const bstr = atob(arr[arr.length - 1])
    const len = bstr.length
    const bytes = new Uint8Array(len);

    for (let i = 0; i < len; i++) {
        bytes[i] = bstr.charCodeAt(i);
    }

    const blob = new Blob([bytes], { type: mime });

    return new File([blob], filename + '.' + ext, { type: mime, lastModified: Date.now() });
}

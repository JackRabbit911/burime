import type { Branch, Info } from "../bootstrap/types"

export const branchInit = () => ({
    id: null,
    parent_id: null,
    owner: 0,
    title: '',
    role: 0,
    age_limit: 0,
    cover: null,
    info: {
        moderation: 0,
        allow_comments: 1,
        signature: 0,
        post_size: 200,
        time_limit: 120,
        description: '',
        rules: '',
        bg_color: '#eeeeee',
        text_color: '#333333',
        text_size: 12,
        cover: '',
        bg_img: '',
    },
    genres: [],
    authors: [],
})

export const calcSelectedGenres = (branch: Branch, genreID: number): Branch => {
    const isExist = branch.genres.includes(genreID)

    if (isExist) {
        return {
            ...branch,
            genres: branch.genres.filter((currentGenreId) => currentGenreId !== genreID)
        }
    }

    return {
        ...branch,
        genres: [...branch?.genres, genreID]
    }
}

export function toggleInfo(key: keyof Info) {
    return function (branch: Branch) {
        const info: Info = {
            ...branch.info,
            [key]: branch.info[key] === 0 ? 1 : 0,
        }

        return {
            ...branch,
            info,
        }
    }
}

export function numberInfoUpdate(key: keyof Info) {
    return function (branch: Branch, value: number) {
        const info: Info = {
            ...branch.info,
            [key]: value,
        }

        return {
            ...branch,
            info,
        }
    }
}

export function textInfoUpdate(key: keyof Info) {
    return function (branch: Branch, value: string) {
        const info: Info = {
            ...branch.info,
            [key]: value,
        }

        return {
            ...branch,
            info,
        }
    }
}

export const getBranchMasterId = (branch: Branch): number | null => {
    const master = branch.authors.find(
        ({ role }) => role === 150
    )

    return !master ? null : master.id
}

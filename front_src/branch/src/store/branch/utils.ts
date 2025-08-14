import type { Branch, Info } from "../vocabularies/types";

export const calcSelectedGenres = (
    branch: Branch | null,
    genreID: number,
): Branch | null => {
    if (!branch) {
        return null;
    }

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
    return function (branch: Branch | null) {
        if (!branch) {
            return null;
        }

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
    return function (branch: Branch | null, value: number) {
        if (!branch) {
            return null;
        }

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
    return function (branch: Branch | null, value: string) {
        if (!branch) {
            return null;
        }

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
 

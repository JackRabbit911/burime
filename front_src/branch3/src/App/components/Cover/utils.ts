import { type OwnAuthors } from "schema/authors";
import type { CoverFile, Genres } from "schema/input";

function base64ToFile(data: CoverFile | null) {
    if (!data) {
        return null
    }
    
    const binaryString = atob(data.base64);

    const len = binaryString.length;
    const bytes = new Uint8Array(len);
    
    for (let i = 0; i < len; i++) {
        bytes[i] = binaryString.charCodeAt(i);
    }

    const blob = new Blob([bytes], { type: data.mime });

    return new File([blob], data.filename, { type: data.mime, lastModified: Date.now() });
}

export function coverFileToUrl(data: CoverFile | null) {
    if (!data) {
        return ''
    }

    const file = base64ToFile(data)

    return file ? URL.createObjectURL(file) : ''
}

export const getGenreString = (
    totalGenres: Genres,
    branchGenres: number[],
) =>
    [...totalGenres[1], ...totalGenres[2]]
        .filter((genre) => branchGenres.includes(genre.id))
        .map((genre) => genre.title)
        .join(', ') ||
    totalGenres[0]
        .filter((genre) => branchGenres.includes(genre.id))
        .map((genre) => genre.title)
        .join(', ') || 'Unknown genre'

export const getMasterAlias = (ownAuthors: OwnAuthors, masterId: number) => {
    const master = ownAuthors.filter((author) => author.id === masterId)
    return master[0].alias
}

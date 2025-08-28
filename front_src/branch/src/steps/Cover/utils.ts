import type { SameWeightGenres } from "../../store/bootstrap/types";

export const getGenreString = (totalGenres: SameWeightGenres[], branchGenres: number[]) => {
    const genreStr = [...totalGenres[1].genres, ...totalGenres[2].genres]
        .filter((genre) => branchGenres.includes(genre.id))
        .map((genre) => genre.title).join(', ')

    return genreStr ? genreStr : [...totalGenres[0].genres]
        .filter((genre) => branchGenres.includes(genre.id))
        .map((genre) => genre.title).join(', ')
}

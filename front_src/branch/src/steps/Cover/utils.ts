import type { SameWeightGenres } from "../../store/bootstrap/types";

export const getGenreString = (
    totalGenres: SameWeightGenres[],
    branchGenres: number[],
) =>
    [...totalGenres[1].genres, ...totalGenres[2].genres]
        .filter((genre) => branchGenres.includes(genre.id))
        .map((genre) => genre.title)
        .join(', ') ||
    totalGenres[0].genres
        .filter((genre) => branchGenres.includes(genre.id))
        .map((genre) => genre.title)
        .join(', ') || 'Unknown genre'

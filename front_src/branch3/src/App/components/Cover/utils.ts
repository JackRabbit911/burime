import type { Genre } from "schema/input";

export const fileToUrl = (file: File | null) => file ? URL.createObjectURL(file) : ''

export const getGenreString = (
    totalGenres: Genre[],
    branchGenres: number[],
) => 
    [...totalGenres[1], ...totalGenres[2]]
        .filter((genre) => branchGenres
            .map((item) => Number(item))
            .includes(genre.id))
        .map((genre) => genre.title)
        .join(', ') ||
    totalGenres[0]
        .filter((genre) => branchGenres
            .map((item) => Number(item))
            .includes(genre.id))
        .map((genre) => genre.title)
        .join(', ') || 'Unknown genre'

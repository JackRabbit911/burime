import type { Genre, SameWeightGenres } from "./types";

export const getSameWeightGenres = (genres: Genre[]): SameWeightGenres[] => {
    const result: SameWeightGenres[] = []
    genres.forEach((genre) => {
        const find = result.find((item) => item.weight === genre.weight)

        if (!find) {
            result.push({
                weight: genre.weight,
                genres: [genre]
            })
        } else {
            find.genres.push(genre)
        }
    })

    return result
}

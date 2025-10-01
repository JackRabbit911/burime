import type { Bootstrap, Genre, SameWeightGenres } from "./types";

export const getOrderedSameWeightGenres = (
    _: SameWeightGenres[],
    result: Bootstrap,
): SameWeightGenres[] =>
    sortByWeight(
        (result?.genres || []).reduce(
            prepareWeightOrderedGenres,
            [] as SameWeightGenres[],
        ),
    )

function prepareWeightOrderedGenres(
    result: SameWeightGenres[],
    genre: Genre
) {
    if (!result.find(
        (weightOrderedGenre) => weightOrderedGenre.weight === genre.weight
    )) {
        return [
            ...result,
            {
                weight: genre.weight,
                genres: [genre],
            }
        ]
    }

    return result.map(
        (weightOrderedGenre) => weightOrderedGenre.weight === genre.weight ?
            { ...weightOrderedGenre, genres: [...weightOrderedGenre.genres, genre] } :
            weightOrderedGenre
    )
}

function sortByWeight(genres: SameWeightGenres[]) {
    return genres.sort(
        ({ weight: weightA }, { weight: weightB }) => weightA - weightB
    )
}
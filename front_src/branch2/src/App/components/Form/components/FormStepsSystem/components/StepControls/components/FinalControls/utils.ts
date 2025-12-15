import type { SameWeightGenres } from "store/bootstrap/types";

export const getSelectedGenreIds = (
  genres: boolean[][],
  sameWeightGenres: SameWeightGenres[],
) => {
  const selectedGenresIds: number[] = [];

  sameWeightGenres.forEach(
    (orgeredGenres, key0) => {
      orgeredGenres.genres.forEach(
        ({ id }, key1) => {
          if (genres?.[key0]?.[key1]) {
            selectedGenresIds.push(id);
          }
        },
      );
    },
  );

  return selectedGenresIds;
};

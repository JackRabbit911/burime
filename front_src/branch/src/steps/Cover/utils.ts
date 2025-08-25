import type { BranchAuthor } from "../../store/authors/types";
import type { SameWeightGenres } from "../../store/bootstrap/types";

export const getMasterAlias = (authors: BranchAuthor[]) => (
    authors.find((author) => author.role === 150)?.alias
)

export const getGenreString = (totalGenres: SameWeightGenres[], branchGenres: number[]) =>
    [...totalGenres[1].genres, ...totalGenres[2].genres]
        .filter((genre) => branchGenres.includes(genre.id))
        .map((genre) => genre.title).join(', ')

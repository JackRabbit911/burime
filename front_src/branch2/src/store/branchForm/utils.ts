import type { Branch, SameWeightGenres } from "store/bootstrap/types";
import type { BranchFormValues } from "./types";

export const getBranchFormTitle = ({
    id = 0,
    title = '',
}: Branch) => [
    id ? 'Edit book' : 'Create book',
    title,
].filter(Boolean).join(': ');

export const getBranchFormValues = (
    branch: Branch,
    sameWeightGenres: SameWeightGenres[]
): BranchFormValues => {
    const title = branch?.title || '';

    const genres = (sameWeightGenres || []).map(
        ({ genres }) => (genres || []).map(
            ({ id = 0 }) => (branch?.genres || []).includes(id),
        ),
    );

    return {
        title,
        genres,
    }

};
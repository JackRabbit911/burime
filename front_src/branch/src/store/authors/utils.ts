import type { Branch } from "../vocabularies/types";
import type { BranchAuthor } from "./types";

export const selectMaster = (branch: Branch, id: string): Branch => {
    // убрать всех с ролью 150
    const authors = branch.authors.filter(
        ({role}) => role < 150
    )

    // добавить единственного с ролью 150
    const master = {
        id: Number(id),
        role: 150,
        status: 100,
    }

    return {
        ...branch,
        authors: [...authors, master],
    }
}

export const addAuthor = (branch: Branch, id: number): Branch => {
    if (isAuthorInBranch(branch.authors, id)) {
        return branch
    }

    const author = {
        id: id,
        role: 50,
        status: 70,
    }

    return {
        ...branch,
        authors: [...branch.authors, author],
    }
}

export function isAuthorInBranch (
    array: BranchAuthor[],
    id: number,
): boolean {
    return Boolean (
        array.find((elem: BranchAuthor) => elem.id === id)
    )
}

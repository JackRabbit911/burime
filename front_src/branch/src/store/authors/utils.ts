import type { Branch } from "../vocabularies/types";
import type { BranchAuthor } from "./types";

export const selectMaster = (branch: Branch | null, id: string): Branch | null => {
    if (!branch) {
        return null;
    }

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

export const addAuthor = (branch: Branch | null, id: number): Branch | null => {
    if (!branch) {
        return null;
    }

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

export const isAuthorInBranch = (array: BranchAuthor[], id: number): boolean => {
    const index = array.findIndex((elem: BranchAuthor) => elem.id === id)

    return index === -1 ? false : true
}

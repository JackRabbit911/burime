import type { Branch } from "../bootstrap/types";
import type { Author, BranchAuthor } from "./types";

export const selectMaster = (branch: Branch, author?: Author): Branch => {
    if (!author) {
        return branch
    }

    // убрать всех с ролью 150
    const authors = branch.authors.filter(
        ({ role }) => role < 150
    )

    // добавить единственного с ролью 150
    return {
        ...branch,
        authors: [...authors, {
            ...author,
            role: 150,
            status: 100,
        }],
    }
}

export const addAuthor = (branch: Branch, author: Author): Branch => {
    if (isAuthorInBranch(branch.authors, author.id)) {
        return branch
    }

    return {
        ...branch,
        authors: [...branch.authors, {
            ...author,
            role: 50,
            status: 70,
        }],
    }
}

export const removeAuthor = (branch: Branch, author: BranchAuthor): Branch => {
    const authors = branch.authors.filter(
        ({ id }) => id !== author.id
    )

    return { ...branch, authors: authors }
}

export const authorRoleChange = (branch: Branch, author: BranchAuthor): Branch => ({
    ...branch,
    authors: branch.authors.map(
        (branchAuthor) => branchAuthor.id !== author.id ? branchAuthor : {
            ...branchAuthor,
            role: branchAuthor.role === 50 ? 100 : 100,
        },
    ),
})

export function isAuthorInBranch(
    array: BranchAuthor[],
    id: number,
): boolean {
    return Boolean(
        array.find((elem: BranchAuthor) => elem.id === id)
    )
}

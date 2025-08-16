import type { Branch } from "../vocabularies/types";

export const selectMaster = (branch: Branch | null, id: string): Branch | null => {
     if (!branch) {
        return null;
    }

    // убрать всех с ролью 150
    const authors = branch.authors.filter(
        ({role}) => role < 150
    )

    // добать единственного с ролью 150
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

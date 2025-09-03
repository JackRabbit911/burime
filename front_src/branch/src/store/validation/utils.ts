import type { BranchAuthor } from "../../store/authors/types";

export const getMasterAlias = (authors: BranchAuthor[]) => (
    authors.find((author) => author.role === 150)?.alias
)

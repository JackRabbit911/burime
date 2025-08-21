export type Author = {
    id: number;
    alias: string;
}

export type Authors = {
    authors: Author[];
    authorsCount: number;
    ownAuthors: Author[];
}

export type BranchAuthor = {
    id: number;
    role: number;
    status: number;
    alias: string;
}

export type AuthorsPayload = {
    filter: string;
    search: string;
}

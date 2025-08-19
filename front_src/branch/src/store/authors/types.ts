export type Author = {
    id: number;
    alias: string;
}

export type Authors = {
    ownAuthors: Author[];
    authors: Author[];
}

export type BranchAuthor = {
    id: number;
    role: number;
    status: number;
    alias: string;
}

export type AuthorsPayload = {
    filter: string;
}

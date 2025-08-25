import type { BranchAuthor } from "../authors/types";

export type Info = {
    moderation: number;
    allow_comments: number;
    signature: number;
    post_size: number;
    time_limit: number;
    description: string;
    rules:string;
    bg_color: string;
    text_color: string;
    text_size: number;
    cover: string;
}

export type Genre = {
    id: number;
    title: string;
    weight: number;
}

export type Branch = {
    id: number | null;
    parent_id: number | null;
    owner: number | null;
    title: string | null;
    role: number;
    age_limit: number;
    cover: string | null;
    info: Info;
    genres: number[];
    authors: BranchAuthor[];
}

export type Bootstrap = {
    genres: Genre[];
    branch: Branch;
}

export type SameWeightGenres = {
    weight: number;
    genres: Genre[];
};

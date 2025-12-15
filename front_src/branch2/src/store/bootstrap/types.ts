import type { BranchAuthor } from "../authors/types";
import type { Posts } from "../posts/types";

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
    bg_img: string;
}

export type Genre = {
    id: number;
    title: string;
    weight: number;
    checked?: boolean;
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

export type CoverFile = {
    filename: string;
    mime: string;
    base64: string;
}

export type FilesBase64 = {
    cover: CoverFile | null;
    bg_img: CoverFile | null;
}

export type Bootstrap = {
    genres: Genre[];
    branch: Branch;
    posts: Posts;
    files: FilesBase64;
}

export type SameWeightGenres = {
    weight: number;
    genres: Genre[];
};


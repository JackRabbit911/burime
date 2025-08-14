export type Genre = {
    id: number;
    title: string;
    weight: number;
}

export type Info = {
    moderation: number;
    allow_comments: number;
    signature: number;
    post_size: number;
    time_limit: number;
    description: string;
    rules:string;
}

export type Branch = {
    id: number | null;
    parent_id: number | null;
    owner: number;
    title: string | null;
    role: number;
    age_limit: number;
    cover: string | null;
    info: Info;
    genres: number[];
}

export type Vocabularies = {
    genres: Genre[];
    branch: Branch;
}

export type SameWeightGenres = {
    weight: number;
    genres: Genre[];
};

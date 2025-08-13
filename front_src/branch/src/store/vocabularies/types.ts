export type Genre = {
    id: number;
    title: string;
    weight: number;
}

export type Branch = {
    id: number | null;
    parent_id: number | null;
    owner: number;
    title: string | null;
    role: number;
    age_limit: number;
    cover: string | null;
}

export type Vocabularies = {
    genres: Genre[];
    branch: Branch;
}

export type SameWeightGenres = {
    weight: number;
    genres: Genre[];
};

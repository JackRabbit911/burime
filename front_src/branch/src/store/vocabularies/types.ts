export type Genre = {
    id: number;
    title: string;
    weight: number;
};

export type Vocabularies = {
    genres: Genre[];
}

export type SameWeightGenres = {
    weight: number;
    genres: Genre[];
};

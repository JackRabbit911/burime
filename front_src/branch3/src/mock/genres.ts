import * as z from "zod"

const genreSch = z.object({
    title: z.string(),
    id: z.number(),
});

const sameWeightGenres = z.array(genreSch);

export type TotalGenres = z.infer<typeof sameWeightGenres>

export const genres = [
    [
        {
            title: 'Проза',
            id: 1,
        },
        {
            title: 'Поэзия',
            id: 2,
        },
        {
            title: 'Non-fiction',
            id: 3,
        },
    ],
    [
        {
            title: 'Роман',
            id: 4,
        },
        {
            title: 'Поэма',
            id: 5,
        },
        {
            title: 'Эпос',
            id: 6,
        },
    ],
] as TotalGenres[]

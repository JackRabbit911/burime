import * as yup from "yup";

const regexpInvalidSymbols = /[<>;]/;

export const schema = yup.object({
    title: yup.string().required().test({
        name: 'title_not_empty_less_8_words',
        test: (value, ctx) => {
            if (value.split(' ').filter(Boolean).length > 8) {
                return ctx.createError({ message: 'Up to 8 words' });
            }

            if (regexpInvalidSymbols.test(value)) {
                return ctx.createError({ message: 'Invalid data' });
            }

            return true;
        },
    }),
    genres: yup.array<boolean[][]>().required().test({
        name: 'genres_not_empty',
        test: (value: boolean[][], ctx) => {
            const isAllUnchecked = value.every(
                (genres) => genres.every(
                    (checked) => !checked,
                ),
            );

            if (isAllUnchecked) {
                return ctx.createError({
                    message: 'Выберите хотя бы 1 жанр',
                });
            }

            return true;
        },
    }),
});

import * as yup from "yup"

const regexpInvalidSymbols = /[<>;]/

export const schema = yup.object({
    title: yup.string().required()
        .test({
            name: 'title',
            test(value, ctx) {

                if (value.split(' ').filter(Boolean).length > 8) {
                    return ctx.createError({ message: 'Up to 8 words' });
                }

                if (regexpInvalidSymbols.test(value)) {
                    return ctx.createError({ message: 'Invalid data' });
                }

                return true;
            },
        }),
    sameWeightGenres: yup.array().of(
        yup.object().required().shape({
            weight: yup.number().required(),
            genres: yup.array().of(
                yup.object().required().shape({
                    id: yup.number().required(),
                    title: yup.string().required(),
                    weight: yup.number().required(),
                    checked: yup.boolean().required(),
                })
            )
        })
    )

})

export type FormSchema = yup.InferType<typeof schema>;

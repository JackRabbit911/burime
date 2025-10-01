import { combine } from "effector";
import { $branch } from "../branch";
import { getMasterAlias } from "./utils";

export const $requiredFields = combine(
    $branch,
    ({ authors, genres, title, info }) => ({
        authorExists: Boolean(getMasterAlias(authors)),
        genresExists: genres.length > 0,
        titleExists: Boolean(title),
        descriptionExists: Boolean(info.description),
        rulesExists: Boolean(info.rules),
        coverExists: Boolean(info.bg_img) ||
            Boolean(info.cover) ||
            info.bg_color !== '#eeeeee' ||
            info.text_color !== '#333333',
    })
)

export const $readyToPublish = combine($requiredFields, ({
    authorExists,
    genresExists,
    titleExists,
}) => authorExists && genresExists && titleExists)

export const $readyProgress = combine($requiredFields, ({
    authorExists,
    genresExists,
    titleExists,
    descriptionExists,
    rulesExists,
    coverExists,
}) => {
        const t = titleExists ? 30 : 0
        const a = authorExists ? 20 : 0
        const g = genresExists ? 20 : 0
        const d = descriptionExists ? 15 : 0
        const r = rulesExists ? 5 : 0
        const c = coverExists ? 10 : 0

        return t + a + g + d + r + c
    }
)

export const $recommendations = combine($requiredFields, ({
    authorExists,
    genresExists,
    titleExists,
    descriptionExists,
    rulesExists,
    coverExists,
}) => {
    const result = new Array()

        if (!genresExists) {
            result.push({ title: 'You need to choose at least one genre', weight: 1 })
        }

        if (!titleExists) {
            result.push({ title: 'You need to create title of Your book', weight: 1 })
        }

        if (!authorExists) {
            result.push({ title: 'You need to choose Team leader of this project', weight: 1 })
        }

        if (!descriptionExists) {
            result.push({ title: 'Create a description for Your work', weight: 0 })
        }

        if (!rulesExists) {
            result.push({ title: 'formulate the private rules of this branch', weight: 0 })
        }

        if (!coverExists) {
            result.push({ title: 'Design Your book cover', weight: 0 })
        }

        return result
    }
)

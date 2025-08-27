import { combine } from "effector";
import { $branch } from "../branch";
import { getMasterAlias } from "./utils";

export const $requiredFields = combine($branch, ({ authors, genres, title }) => {
    const author = getMasterAlias(authors)

    return {
        authorExists: typeof author === 'undefined' ? false : true,
        genresExists: genres.length === 0 ? false : true,
        titleExists: !title ? false : true,
    }
})

export const $readyToPublish = combine($requiredFields, ({
    authorExists,
    genresExists,
    titleExists
}) => authorExists && genresExists && titleExists)

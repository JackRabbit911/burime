import { combine } from "effector";
import { $branch } from "../branch";
import { getMasterAlias } from "./utils";

export const $requiredFields = combine(
    $branch,
    ({ authors, genres, title }) => ({
        authorExists: Boolean(getMasterAlias(authors)),
        genresExists: genres.length > 0,
        titleExists: Boolean(title),
    })
)

export const $readyToPublish = combine($requiredFields, ({
    authorExists,
    genresExists,
    titleExists
}) => authorExists && genresExists && titleExists)

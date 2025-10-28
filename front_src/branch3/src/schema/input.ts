import * as z from "zod"
import { ownAuthors } from "./authors"

const info = z.strictObject({
    moderation: z.number(),
    allow_comments: z.number(),
    signature: z.number(),
    post_size: z.number(),
    time_limit: z.number(),
    description: z.string(),
    rules: z.string(),
    bg_color: z.string(),
    text_color: z.string(),
    text_size: z.number(),
    cover: z.string(),
    bg_img: z.string(),
})

const member = z.object({
    id: z.number().positive(),
    role: z.number().positive(),
    status: z.number().positive(),
    alias: z.string(),
})

const genre = z.object({
    id: z.number().positive(),
    title: z.string(),
})

const coverFile = z.object({
    filename: z.string(),
    mime: z.string(),
    base64: z.string(),
})

const filesBase64 = z.object({
    cover: coverFile.nullable(),
    bg_img: coverFile.nullable(),
})

const post = z.object({
    id: z.number().nullable(),
    body: z.string(),
})

const posts = z.object({
    first: post,
    last: post,
})

const genresSch = z.array(z.array(genre))

const branch = z.strictObject({
    id: z.number().positive().nullable(),
    parent_id: z.number().positive().nullable(),
    owner: z.number().positive().nullable(),
    title: z.string(),
    role: z.number().nonnegative(),
    age_limit: z.number().nonnegative(),
    info: info,
    genres: z.array(z.number().positive().nullable()),
    members: z.array(member),
})

export const bootstrapSch = z.object({
    genres: genresSch,
    branch: branch,
    posts: posts,
    files: filesBase64,
    ownAuthors: ownAuthors,
})

export type Bootstrap = z.infer<typeof bootstrapSch>

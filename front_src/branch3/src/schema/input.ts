import * as z from "zod"
import { member, ownAuthors } from "./authors"
import { info, posts } from "./output"

const genre = z.object({
    id: z.number().positive(),
    title: z.string(),
})

const filesBase64 = z.object({
    cover: z.string().nullable(),
    bg_img: z.string().nullable(),
})

const genreSch = z.array(genre)

const authorsFilters = z.array(z.string())
const authorsPermissions = z.object({}).catchall(z.number())
const authorsStatuses = z.object({}).catchall(z.number())

const branch = z.strictObject({
    id: z.number().positive().nullable(),
    parent_id: z.number().positive().nullable(),
    owner: z.number().positive().nullable(),
    title: z.string(),
    role: z.number().nonnegative(),
    age_limit: z.number().nonnegative(),
    info: info,
})

export const bootstrapSch = z.object({
    total_genres: z.array(genreSch),
    branch_genres: z.array(z.number().positive()),
    branch: branch,
    members: z.array(member),
    posts: posts,
    files: filesBase64,
    ownAuthors: ownAuthors,
    authorsFilters,
    authorsPermissions,
    authorsStatuses,
    draft: z.number().positive().nullable().optional(),
})

export type Bootstrap = z.infer<typeof bootstrapSch>
export type Genre = z.infer<typeof genreSch>
export type AuthorsFilters = z.infer<typeof authorsFilters>
export type Statuses = z.infer<typeof authorsStatuses>
export type Permissions = z.infer<typeof authorsPermissions>

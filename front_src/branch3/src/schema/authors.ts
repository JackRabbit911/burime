import * as z from "zod"

export const branchAuthor = z.object({
    id: z.number().int().positive(),
    role: z.number().int().min(0).max(255),
    status: z.number().int().min(0).max(255),
    alias: z.string(),
})

export const authorsSearch = z.string()
    .trim()
    .regex(/^[^<>]*$/, 'Invalid input!')
    .optional()

export const authorsFilter = z.string()
    .trim()
    .regex(/^[^<>;]*$/, 'Invalid input!')
    .optional()

const author = z.object({
    id: z.number().positive(),
    alias: z.string(),
})

const authors = z.object({
    authors: z.array(author),
    authorsCount: z.number().nonnegative().int(),
    ownAuthors:  z.array(author),
})

export const authorsPayload = z.object({
    filter: z.optional(authorsFilter),
    search: z.optional(authorsSearch),
}).optional()

export const ownAuthors = z.array(author)

export type BranchAuthor = z.infer<typeof branchAuthor>
export type Author = z.infer<typeof author>
export type Authors = z.infer<typeof authors>
export type OwnAuthors = z.infer<typeof ownAuthors>
export type AuthorsPayload = z.infer<typeof authorsPayload>

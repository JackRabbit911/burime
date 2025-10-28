import * as z from "zod"

export const member = z.object({
    id: z.number().int().positive(),
    role: z.number().int().min(0).max(255),
    status: z.number().int().min(0).max(255),
    alias: z.string(),
})

export const authorsSearch = z.string()
    .trim()
    .regex(/^[^<>]*$/, 'Invalid input!')
    .nullable()
    .optional()

export const authorsFilter = z.string()
    .trim()
    .regex(/^[^<>;]*$/, 'Invalid input!')
    .nullable()
    .optional()

const author = z.object({
    id: z.number().positive(),
    alias: z.string(),
})

const authors = z.object({
    list: z.array(author),
    count: z.number().nonnegative().int(),
})

export const authorsPayload = z.object({
    filter: z.optional(authorsFilter),
    search: z.optional(authorsSearch),
    page: z.optional(z.number().positive()),
    limit: z.optional(z.number().positive()),
}).optional()

export const ownAuthors = z.array(author)

export type Member = z.infer<typeof member>
export type Author = z.infer<typeof author>
export type Authors = z.infer<typeof authors>
export type OwnAuthors = z.infer<typeof ownAuthors>
export type AuthorsPayload = z.infer<typeof authorsPayload>

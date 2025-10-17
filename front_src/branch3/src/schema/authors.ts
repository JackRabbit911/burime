import * as z from "zod"

export const branchAuthor = z.object({
    id: z.number().int().positive(),
    role: z.number().int().min(0).max(255),
    status: z.number().int().min(0).max(255),
    alias: z.string(),
})

const author = z.object({
    id: z.number().positive(),
    alias: z.string(),
})

const authors = z.object({
    authors: z.array(author),
    authorsCount: z.number().nonnegative().int(),
    ownAuthors:  z.array(author),
})

export const ownAuthors = z.array(author)

export type BranchAuthor = z.infer<typeof branchAuthor>
export type Authors = z.infer<typeof authors>
export type OwnAuthors = z.infer<typeof ownAuthors>

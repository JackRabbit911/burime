import * as z from "zod"

const author = z.object({
    id: z.number().positive(),
    alias: z.string(),
})

const authors = z.object({
    authors: z.array(author),
    authorsCount: z.number().nonnegative().int(),
    ownAuthors:  z.array(author),
})

export type Authors = z.infer<typeof authors>

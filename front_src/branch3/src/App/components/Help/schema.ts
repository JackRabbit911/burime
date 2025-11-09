import * as z from "zod"

const helpSch = z.object({
    step: z.number().int().positive(),
    body: z.string(),
})

export type Help = z.infer<typeof helpSch>

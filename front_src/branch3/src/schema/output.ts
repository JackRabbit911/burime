import * as z from "zod"
import { authorsPayload, member, slimMember } from "./authors";
import { imageFile } from "./files";

const branchTitle = z.string()
  .trim()
  .min(1, { message: 'Required' })
  .regex(/^[^<>;]*$/, 'Invalid input!')
  .refine((value) => value.trim().split(' ').length <= 3, 'Up to 3 words!')

const regString = z.string().trim().regex(/^[^<>;]*$/, 'Invalid input!')

const intro = (max: number) => z.string()
  .trim()
  .regex(/^[^<>;]*$/, 'Invalid input!')
  .refine((value) => value.trim().split(' ').length <= max, `Up to ${max} words!`)

const post = z.object({
    id: z.number().nullable().optional(),
    body: intro(200),
    author_id: z.coerce.number().nullable(),
})

export const posts = z.object({
    first: post,
    last: post,
})

export const info = z.object({
  allow_comments: z.coerce.number().min(0).max(1),
  moderation: z.coerce.number().min(0).max(1),
  signature: z.coerce.number().min(0).max(1),
  post_size: z.number().int().positive(),
  time_limit: z.number().int().positive(),
  bg_color: z.string().regex(/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/, "Invalid hex color format."),
  text_color: z.string().regex(/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/, "Invalid hex color format."),
  text_size: z.coerce.number().int().min(5).max(50),
  bg_img: regString,
  cover: regString,
  rules: regString,
  description: regString,
})

export const branch = z.object({
  id: z.number().int().positive().nullable(),
  parent_id: z.number().int().positive().nullable(),
  owner: z.number().int().positive().nullable(),
  title: branchTitle,
  role: z.coerce.number().int().nonnegative(),
  age_limit: z.coerce.number().int().nonnegative().max(21),
  info: info,
}).required({title: true})

export const formSchema = z.object({
  branch: branch,
  branch_genres: z.array(z.coerce.number()),
  members: z.array(member).min(1, { message: "Please select at least team leader." }),
  posts: posts,
  masterId: z.coerce.number().positive(),
  bgImg: imageFile,
  cover: imageFile,
  authorsPayload,
});

export const draftSchema = formSchema.omit({
  masterId: true,
  authorsPayload: true,
}).extend({
  draft: z.number().positive().nullable().optional()
})

export const finalSchema = draftSchema.extend({
  members: z.array(slimMember),
  branch_genres: z.array(z.coerce.number()).min(1, { message: "Please select at least one option." }),
})

export type FormData = z.infer<typeof finalSchema>
export type DraftData = z.infer<typeof draftSchema>

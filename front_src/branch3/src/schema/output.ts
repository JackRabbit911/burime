import * as z from "zod"
import { authorsPayload, member } from "./authors";
import { imageFile } from "./files";

export const branchTitle = z.string()
  .trim()
  .min(1, { message: 'Required' })
  .regex(/^[^<>;]*$/, 'Invalid input!')
  .refine((value) => value.trim().split(' ').length <= 3, 'Up to 3 words!')

const intro = (max: number) => z.string()
  .trim()
  .regex(/^[^<>;]*$/, 'Invalid input!')
  .refine((value) => value.trim().split(' ').length <= max, `Up to ${max} words!`)

export const formSchema = z.object({
  branchId: z.number().int().positive().nullable(),
  branchTitle,
  genres: z.array(z.coerce.number()).min(1, { message: "Please select at least one option." }),
  branchRole: z.coerce.number().int().min(0).max(2),
  moderation: z.boolean(),
  comments: z.boolean(),
  signature: z.boolean(),
  ageLimit: z.coerce.number().int().nonnegative().max(21),
  postSize: z.coerce.number().int().positive(),
  timeLimit: z.coerce.number().int().positive(),
  description: intro(200),
  rules: intro(200),
  masterId: z.coerce.number().positive(),
  members: z.array(member),
  bg_color: z.string().regex(/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/, "Invalid hex color format."),
  text_size: z.coerce.number().int().min(5).max(50),
  text_color: z.string().regex(/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/, "Invalid hex color format."),
  bgImg: imageFile,
  cover: imageFile,
  authorsPayload,
  firstPost: intro(200),
  lastPost: intro(200),
});

export const finalSchema = formSchema.omit({
  authorsPayload: true,
})

export type FormData = z.infer<typeof finalSchema>

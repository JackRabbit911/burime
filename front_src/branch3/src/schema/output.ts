import * as z from "zod"

const branchTitle = z.string()
  .trim()
  .min(1, { message: 'Required' })
  .regex(/^[^<>;]*$/, 'Invalid input!')
  .refine((value) => value.trim().split(' ').length <= 3, 'Up to 3 words!')

export const formSchema = z.object({
  branchTitle,
  genres: z.array(z.coerce.number()).min(1, { message: "Please select at least one option." }),
  branchRole: z.coerce.number().nonnegative(),
  moderation: z.boolean(),
  comments: z.boolean(),
  signature: z.boolean(),
  ageLimit: z.coerce.number().nonnegative(),
  postSize: z.coerce.number().positive(),
  timeLimit: z.coerce.number().positive(),
  description: z.string().trim().regex(/^[^<>;]*$/, 'Invalid input!'),
  rules: z.string().trim().regex(/^[^<>;]*$/, 'Invalid input!'),
});

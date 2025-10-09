import * as z from "zod"

const branchTitle = z.string()
  .min(1, { message: 'Required' })
  .regex(/^[^<>;]*$/, 'Invalid input!')
  .refine((value) => value.trim().split(' ').length <= 3, 'Up to 3 words!')

export const formSchema = z.object({
  branchTitle,
  genres: z.array(z.coerce.number()).min(1, { message: "Please select at least one option." }),
});

import { FormProvider, useForm } from "react-hook-form"
import * as z from "zod"
import { zodResolver } from "@hookform/resolvers/zod"

import { genres } from "mock/genres"
import Wrapper from "reused/Wrapper"
import Title from "./components/Title"
import Genres from "./components/Genres"

const branchTitle = z.string()
  .min(1, { message: 'Required' })
  .regex(/^[^<>;]*$/, 'Invalid input!')
  .refine((value) => value.trim().split(' ').length <= 3, 'Up to 3 words!')

const formSchema = z.object({
  branchTitle,
  genres: z.array(z.coerce.number()).min(1, { message: "Please select at least one option." }),
});

const branchGenres: number[] = []// [1, 2]

const App = () => {
  const methods = useForm({
    resolver: zodResolver(formSchema),
    mode: "all",
    defaultValues: {
      branchTitle: 'rer',
      genres: branchGenres,
    },
  });

  console.log(genres, methods.formState.errors.genres, methods.getValues())

  return (
    <>
      <FormProvider {...methods}>
        <Wrapper title="Laboratorium">
          <Title />
          <Genres genres={genres} checked={branchGenres} />
        </Wrapper>
      </FormProvider>
    </>
  );
};

export default App;



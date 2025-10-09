import { zodResolver } from "@hookform/resolvers/zod";
import { FormProvider, useForm } from "react-hook-form";

import Wrapper from "reused/Wrapper";
import { formSchema } from "schema/output";
import Title from "../Title";
import Genres from "../Genres";
import { genres } from "mock/genres";

const branchGenres: number[] = []// [1, 2]

const Form = () => {
  const methods = useForm({
    resolver: zodResolver(formSchema),
    mode: "all",
    defaultValues: {
      branchTitle: 'rer',
      genres: branchGenres,
    },
  });

  console.log(methods.getValues())

  return (
    <FormProvider {...methods}>
      <Wrapper title="Laboratorium">
        <Title />
        <Genres genres={genres} checked={branchGenres} />
      </Wrapper>
    </FormProvider>
  )
}

export default Form

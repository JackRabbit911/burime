import { zodResolver } from "@hookform/resolvers/zod";
import { FormProvider, useForm } from "react-hook-form";

import Wrapper from "reused/Wrapper";
import { formSchema } from "schema/output";
import Title from "../Title";
import Genres from "../Genres";
import type { Bootstrap } from "schema/input";

type Props = {
  bootstrap: Bootstrap;
}

const Form = ({ bootstrap: bootstrap }: Props) => {
  const branchGenres = bootstrap?.branch.genres as number[];

  const methods = useForm({
    resolver: zodResolver(formSchema),
    mode: "all",
    defaultValues: {
      branchTitle: bootstrap?.branch.title || '',
      genres: bootstrap?.branch.genres || [],
    },
  });

  return (
    <FormProvider {...methods}>
      <Wrapper title="Laboratorium">
        <Title />
        <Genres genres={bootstrap?.genres || []} checked={branchGenres} />
      </Wrapper>
    </FormProvider>
  )
}

export default Form

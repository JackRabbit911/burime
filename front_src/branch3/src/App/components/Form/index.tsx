import { zodResolver } from "@hookform/resolvers/zod";
import { FormProvider, useForm } from "react-hook-form";

import Wrapper from "reused/Wrapper";
import { formSchema } from "schema/output";
import Title from "../Title";
import Genres from "../Genres";
import type { Bootstrap } from "schema/input";
import Steps from "../Steps";
import StepControls from "../StepControls";

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
      genres: branchGenres || [],
    },
  });

  return (
    <FormProvider {...methods}>
      <Wrapper title="Laboratorium">
        <Title />
        <Steps />
        <Genres genres={bootstrap?.genres || []} checked={branchGenres} />
        <StepControls />
      </Wrapper>
    </FormProvider>
  )
}

export default Form

import { zodResolver } from "@hookform/resolvers/zod";
import { FormProvider, useForm } from "react-hook-form";

import Wrapper from "reused/Wrapper";
import { formSchema } from "schema/output";
import Title from "../Title";
import Genres from "../Genres";
import type { Bootstrap } from "schema/input";
import Steps from "../Steps";
import StepControls from "../StepControls";
import Rules from "../Rules";
import { getDefaults } from "./utils";

type Props = {
  bootstrap: Bootstrap;
}

const Form = ({ bootstrap }: Props) => {
  const branchGenres = bootstrap?.branch.genres as number[];

  const methods = useForm({
    resolver: zodResolver(formSchema),
    mode: "all",
    defaultValues: getDefaults(bootstrap?.branch)
  });

  return (
    <FormProvider {...methods}>
      <Wrapper title="Laboratorium">
        <Title />
        <Steps />
        <Genres genres={bootstrap?.genres || []} checked={branchGenres} />
        <Rules />
        <StepControls />
      </Wrapper>
    </FormProvider>
  )
}

export default Form

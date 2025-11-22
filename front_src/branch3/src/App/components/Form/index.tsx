import { useUnit } from "effector-react";
import { zodResolver } from "@hookform/resolvers/zod";
import { FormProvider, useForm } from "react-hook-form";
import { formSchema } from "schema/output";
import { getDefaults } from "./utils";
import { $step } from "store/step";
import type { Bootstrap } from "schema/input";
import Wrapper from "reused/Wrapper";
import Title from "../Title";
import Genres from "../Genres";
import Steps from "../Steps";
import Rules from "../Rules";
import Authors from "../Authors";
import StepControls from "../StepControls";
import Cover from "../Cover";
import Modal from "reused/Modal";
import Publish from "../Publish";

type Props = {
  bootstrap: Bootstrap;
}

const Form = ({ bootstrap }: Props) => {
  const step = useUnit($step)
  const branchGenres = bootstrap?.branch.genres as number[];

  const methods = useForm({
    resolver: zodResolver(formSchema),
    mode: "all",
    defaultValues: getDefaults(bootstrap)
  });

  return (
    <FormProvider {...methods}>
      <Wrapper title="Laboratorium">
        <Title />
        <Steps />
        {step === 1 ? <Genres genres={bootstrap?.genres || []} checked={branchGenres} /> : null}
        {step === 2 ? <Rules /> : null}
        {step === 3 ? <Authors bootstrap={bootstrap} /> : null}
        {step === 4 ? <Cover /> : null}
        {step === 5 ? <Publish /> : null}
        <StepControls />
        <Modal />
      </Wrapper>
    </FormProvider>
  )
}

export default Form

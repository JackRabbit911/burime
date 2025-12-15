import { useUnit } from "effector-react";
import { zodResolver } from "@hookform/resolvers/zod";
import { FormProvider, useForm } from "react-hook-form";
import { formSchema } from "schema/output";
import { getDefaults, getTitle } from "./utils";
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
import { t } from "i18n/utils";
import { useEffect } from "react";
import { $translate, getTranslateFx } from "i18n/store";
import { $memberId } from "store/authors";

type Props = {
  bootstrap: Bootstrap;
}

const Form = ({ bootstrap }: Props) => {
  const step = useUnit($step)
  const memberId = useUnit($memberId)
  const translate = useUnit($translate)
  const branchGenres = bootstrap?.branch_genres as number[];
  
  const methods = useForm({
    resolver: zodResolver(formSchema),
    mode: "all",
    defaultValues: getDefaults(bootstrap)
  });

  useEffect(() => {
    getTranslateFx(translate)
  }, [step, memberId])

  return (
    <FormProvider {...methods}>
      <Wrapper title={t(getTitle(bootstrap.branch.id))}>
        <Title />
        <Steps />
        {step === 1 ? <Genres genres={bootstrap?.total_genres || []} checked={branchGenres} /> : null}
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

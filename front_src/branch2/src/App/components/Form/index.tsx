import { useEffect } from "react"

import { useUnit } from "effector-react"
import { FormProvider, useForm } from "react-hook-form"
import { yupResolver } from "@hookform/resolvers/yup";

import FormTitle from "./components/FormTitle";
import FormStepsSystem from "./components/FormStepsSystem";
import FormWrapper from "./components/FormWrapper";
import { schema } from "./components/schema";
import { $branchFormValues } from "store"

import type { BranchFormValues } from "store/branchForm/types";

const Form = () => {
  const branchFormValues = useUnit($branchFormValues)

  const methods = useForm<BranchFormValues>({
    defaultValues: branchFormValues,
    mode: "onChange",
    resolver: yupResolver(schema),
  })

  useEffect(() => {
    methods.reset(branchFormValues)
  }, [methods, branchFormValues])

  return (
    <FormProvider {...methods}>
      <FormWrapper>
        <FormTitle />
        <FormStepsSystem />
      </FormWrapper>
    </FormProvider>
  )
}

export default Form

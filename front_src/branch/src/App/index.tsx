import { useEffect } from "react"

import { useUnit } from "effector-react"
import { FormProvider, useForm } from "react-hook-form"

import { appStarted } from "store"
import { $bootstrapStatus } from "../store/bootstrap"
import { $branch, $isBranchLoaded } from "../store/branch"
import Loader from "../reused/Loading"
import Wrapper from "reused/Wrapper"
import ErrorCmp from "reused/ErrorCmp"
import TextInput from "reused/reactHookForms/TextInput"
import { titleRules } from "./rules"

type Form = {
  title: string;
}

function App() {
  const methods = useForm<Form>({
    defaultValues: {
      title: "",
    },
    mode: "onChange",
  })

  const status = useUnit($bootstrapStatus)
  const isBranchLoaded = useUnit($isBranchLoaded)
  const { id, title } = useUnit($branch)
  const h1 = id ? `Edit the book: "${title}"` : `Create the book: "${title}"`

  useEffect(() => {
    appStarted()
  }, [])

  useEffect(() => {
    methods.reset({ title: title || "" })
  }, [methods, title])

  if (status >= 400) {
    return (
      <Wrapper>
        <ErrorCmp status={status} />
      </Wrapper>
    )
  }

  return !isBranchLoaded ? (
    <Loader message="Загрузка" />
  ) : (
    <FormProvider {...methods}>
      <Wrapper title={h1}>
        <TextInput
          label="Title"
          fieldName="title"
          optional="Up to 8 words"
          placeholder="Введите название произведения"
          rules={titleRules} />
      </Wrapper>
    </FormProvider>
  )
}

export default App

import { useEffect } from "react"

import { useUnit } from "effector-react"
import { FormProvider, useForm } from "react-hook-form"
import { yupResolver } from '@hookform/resolvers/yup';

import { $step, appStarted } from "store"
import { $bootstrapStatus } from "../store/bootstrap"
import { $branch, $isBranchLoaded } from "../store/branch"
import Loader from "../reused/Loading"
import Wrapper from "reused/Wrapper"
import ErrorCmp from "reused/ErrorCmp"
import TextInput from "reused/reactHookForms/TextInput"
import { schema, type FormSchema } from "./schema";
import Steps from "steps/Steps";
import Genres from "steps/Genres";
import Rules from "steps/Rules";
import Authors from "steps/Authors";
import Cover from "steps/Cover";
import Publish from "steps/Publish";
import StepControls from "steps/StepControls";
import Dialog from "reused/Dialog";
import type { SameWeightGenres } from "store/bootstrap/types";
// import { titleRules } from "./rules"

const sameWeightGenres: SameWeightGenres[] = [
  {
    weight: 0,
    genres: [
      {
        id: 1,
        title: 'Проза',
        weight: 0,
        checked: true,
      },
      {
        id: 2,
        title: 'Поэзия',
        weight: 0,
        checked: false,
      },
    ],
  },
  {
    weight: 1,
    genres: [
      {
        id: 3,
        title: 'Роман',
        weight: 1,
        checked: true,
      },
      {
        id: 4,
        title: 'Поэма',
        weight: 1,
        checked: false,
      },
    ],
  },
]

type Form = {
  title: string;
  sameWeightGenres: SameWeightGenres[];
}

function App() {
  const methods = useForm<FormSchema>({
    defaultValues: {
      title: "",
      sameWeightGenres: [],
    },
    mode: "onChange",
    resolver: yupResolver(schema),
  })

  const status = useUnit($bootstrapStatus)
  const step = useUnit($step)
  const isBranchLoaded = useUnit($isBranchLoaded)
  const branch= useUnit($branch)
  const h1 = branch.id ? `Edit the book: "${branch.title}"` : `Create the book: "${branch.title}"`

  useEffect(() => {
    appStarted()
  }, [])

  useEffect(() => {
    methods.reset({
      title: branch.title || "",
      sameWeightGenres,
    })
  }, [methods, isBranchLoaded])

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
        // rules={titleRules}
        />
        <Steps step={step} />
        {step === 1 && <Genres />}
        {step === 2 && <Rules />}
        {step === 3 && <Authors />}
        {step === 4 && <Cover />}
        {step === 5 && <Publish />}
        <StepControls />
        <Dialog />
      </Wrapper>
    </FormProvider>
  )
}

export default App

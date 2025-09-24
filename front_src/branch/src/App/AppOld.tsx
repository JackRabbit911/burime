import Genres from "../steps/Genres"
import Rules from "../steps/Rules"
import StepControls from "../steps/StepControls"
import { useUnit } from "effector-react"
import { $step } from "../store/common"
import { useEffect } from "react"
import Authors from "../steps/Authors"
import { $bootstrapStatus } from "../store/bootstrap"
import Cover from "../steps/Cover"
import Publish from "../steps/Publish"
import { $branch, $isBranchLoaded } from "../store/branch"
import Steps from "../steps/Steps"
import TitleInput from "../steps/TileInput"
import Dialog from "../reused/Dialog"
import Loader from "../reused/Loading"
import Wrapper from "reused/Wrapper"
import ErrorCmp from "reused/ErrorCmp"
import { appStarted } from "store"

function App() {
  const status = useUnit($bootstrapStatus)
  const step = useUnit($step)
  const isBranchLoaded = useUnit($isBranchLoaded)
  const { id, title } = useUnit($branch)
  const h1 = id ? `Edit the book: "${title}"` : `Create the book: "${title}"`

  useEffect(() => {
    appStarted()
  }, [])

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
    <Wrapper title={h1}>
      <TitleInput />
      <Steps step={step} />
      {step === 1 && <Genres />}
      {step === 2 && <Rules />}
      {step === 3 && <Authors />}
      {step === 4 && <Cover />}
      {step === 5 && <Publish />}
      <StepControls />
      <Dialog />
    </Wrapper>
  )
}

export default App

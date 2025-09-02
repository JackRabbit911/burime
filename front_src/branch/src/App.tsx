import Genres from "./steps/Genres"
import Rules from "./steps/Rules"
import StepControls from "./steps/StepControls"
import { useUnit } from "effector-react"
import { $step } from "./store/common"
import { useEffect } from "react"
import Authors from "./steps/Authors"
import { getBootstrapFx } from "./store/bootstrap"
import Cover from "./steps/Cover"
import Publish from "./steps/Publish"
import { $branch } from "./store/branch"
import Steps from "./steps/Steps"
import TitleInput from "./steps/TileInput"
import Dialog from "./reused/Dialog"

function App() {
  const step = useUnit($step)
  const { id, title } = useUnit($branch)
  const h1 = id ? `Edit the book: "${title}"` : 'Create the book'

  useEffect(() => {
    getBootstrapFx()
  }, [])

  return (
    <>
      <div className="flex flex-row justify-center">
        <div className="w-full md:w-2xl lg:w-4xl bg-base-100 p-4">
          <h1 className="text-2xl mt-2 mb-3">
            {h1}
          </h1>
          <Steps step={step} />
          <TitleInput />
          {step === 1 && <Genres />}
          {step === 2 && <Rules />}
          {step === 3 && <Authors />}
          {step === 4 && <Cover />}
          {step === 5 && <Publish />}
          <StepControls />
        </div>
      </div>
      <Dialog />
    </>
  )
}

export default App

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
import Step from "./reused/Step"
import { $requiredFields } from "./store/validation"
import { $branch } from "./store/branch"

function App() {
  const step = useUnit($step)
  const { authorExists, titleExists, genresExists } = useUnit($requiredFields)
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
          <ul className="steps w-full my-4">
            <Step
              step={1}
              title="Genres"
              isError={!genresExists && step > 1}
            />
            <Step
              step={2}
              title="Rules"
              isError={!titleExists && step > 2}
            />
            <Step
              step={3}
              title="Authors"
              isError={!authorExists && step > 3}
            />
            <Step
              step={4}
              title="Cover"
            />
            <Step
              step={5}
              title="Publish"
            />
          </ul>
          {step === 1 && <Genres />}
          {step === 2 && <Rules />}
          {step === 3 && <Authors />}
          {step === 4 && <Cover />}
          {step === 5 && <Publish />}
          <StepControls />
        </div>
      </div>
    </>
  )
}

export default App

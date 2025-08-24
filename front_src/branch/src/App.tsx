import Genres from "./steps/Genres"
import Rules from "./steps/Rules"
import StepControls from "./steps/StepControls"
import { useUnit } from "effector-react"
import { $step, stepChanged } from "./store/common"
import { useEffect } from "react"
import Authors from "./steps/Authors"
import { getBootstrapFx } from "./store/bootstrap"
import Cover from "./steps/Cover"

function App() {
  const step = useUnit($step)

  useEffect(() => {
    getBootstrapFx()
  }, [])

  const onStep = (newStep: number) => () => {
    stepChanged(newStep)
  }

  return (
    <>
      <div className="flex flex-row justify-center">
        <div className="w-full md:w-2xl lg:w-4xl bg-base-100 p-4">
          <h1 className="text-2xl mt-2 mb-3">
            Create the book
          </h1>
          <ul className="steps w-full my-4">
            <li className="step step-primary dark:step-info">
              <button className="btn btn-link" onClick={onStep(1)}>
                Genres
              </button>
            </li>
            <li className={step > 1 ? 'step step-primary' : 'step'}>
              <button className="btn btn-link" onClick={onStep(2)}>
                Rules
              </button>
            </li>
            <li className={step > 2 ? 'step step-primary' : 'step'}>
              <button className="btn btn-link" onClick={onStep(3)}>
                Authors
              </button>
            </li>
            <li className={step > 3 ? 'step step-primary' : 'step'}>
              <button className="btn btn-link" onClick={onStep(4)}>
                Cover
              </button>
            </li>
            <li className={step > 4 ? 'step step-primary' : 'step'}>
              <button className="btn btn-link" onClick={onStep(5)}>
                Publish
              </button>
            </li>
          </ul>
          {step===1 &&<Genres />}
          {step===2 &&<Rules />}
          {step===3 &&<Authors />}
          {step===4 &&<Cover />}
          <StepControls />
        </div>
      </div>
    </>
  )
}

export default App

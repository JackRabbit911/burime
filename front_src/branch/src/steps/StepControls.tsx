import { useUnit } from "effector-react"
import { $step, stepChanged } from "../store/common"
import FinalControls from "./FinalControls"
import { componentAdded } from "../reused/Dialog/store"
import Helper from "./Helper"

const StepControls = () => {
  const step = useUnit($step)

  const onNextStep = () => {
    stepChanged(step + 1)
  }

  const onPrevStep = () => {
    stepChanged(step - 1)
  }

  const onClick = (step: number) => () => {
    componentAdded(<Helper step={step} />)
  }

  return (
    <div className="flex flex-row justify-between mt-4">
      <button className="btn btn-primary dark:btn-info" onClick={onPrevStep} disabled={step === 1}>
        Prev
      </button>
      {(step < 5) && (
        <button
          className="btn btn-circle btn-success text-2xl"
          onClick={onClick(step)}
        >
          ?
        </button>
      )}
      {step === 5 ? (
        <FinalControls />
      ) : (
        <button className="btn btn-primary dark:btn-info" onClick={onNextStep}>
          Next
        </button>
      )}
    </div>
  )
}

export default StepControls

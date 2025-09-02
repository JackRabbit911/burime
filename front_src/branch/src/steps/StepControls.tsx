import { useUnit } from "effector-react"
import { $step, stepChanged } from "../store/common"
import FinalControls from "./FinalControls"
import { modalClicked } from "../reused/Dialog/store"

const StepControls = () => {
    const step = useUnit($step)

    const onNextStep = () => {
        stepChanged(step + 1)
    }

    const onPrevStep = () => {
        stepChanged(step - 1)
    }

    return (
        <div className="flex flex-row justify-between mt-4">
            <button className="btn btn-primary dark:btn-info" onClick={onPrevStep} disabled={step===1}>
                Prev
            </button>
            <button
          className="btn btn-link dark:text-info"
          onClick={()=>{modalClicked(true)}}
        >
          More info...
        </button>
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

import { useUnit } from "effector-react"
import RadioBox from "../reused/RadioBox"
import { $selectedRWMode, rwModeToggled } from "../store/branch"

const Rules = () => {
  const role = useUnit($selectedRWMode)

  return (
    <fieldset className="fieldset">
      <div className="grid md:grid-cols-3 gap-4">
        <div>
          <legend className="fieldset-legend ms-0 mx-1 mb-2">
            Read-write mode
          </legend>
          <RadioBox label={'Open'} value={0} checked={role===0} onChange={rwModeToggled} />
          <RadioBox label={'Open-Closed'} value={1} checked={role===1} onChange={rwModeToggled} />
          <RadioBox label={'Commercial'} value={2} checked={role===2} onChange={rwModeToggled} />
          <div className="divider my-1"></div>   
        </div>
        <div className="md:col-span-2">
          {role}
        </div>
      </div>

    </fieldset>
  )
}

export default Rules

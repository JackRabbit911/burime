import { isReady } from "App/utils";
import { useUnit } from "effector-react"
import { useFormContext } from "react-hook-form";
import { $step } from "store/step"
import { isObjectEmpty } from "utils";

const FinalControls = () => {
  const step = useUnit($step)
  const { watch, formState: { errors } } = useFormContext();
  const values = watch()

  return step < 5 ? null : (
    <>
      <div className="flex flex-row justify-between gap-2">
        <button
          className="btn btn-error"
        // onClick={onCancel}
        >
          Cancel
        </button>
        <button
          className="btn"
          disabled={!isObjectEmpty(errors) || !values.branchTitle}
        >
          Draft
        </button>
        <button
          className="btn btn-primary dark:btn-info"
          disabled={!isObjectEmpty(errors) || !isReady(values)}
        // onClick={onPublish}
        >
          Publish
        </button>
      </div>
    </>
  )
}

export default FinalControls

import { isReady } from "App/utils";
import { useUnit } from "effector-react"
import { useFormContext } from "react-hook-form";
import { modalOpened } from "reused/Modal/store";
import { $step } from "store/step"
import CancelDialog from "../CancelDialog";
import { formSchema } from "schema/output";
import { draftClicked, published } from "store/publish";

const FinalControls = () => {
  const step = useUnit($step)
  const [publishEvent, draftEvent] = useUnit([published, draftClicked])
  const { watch } = useFormContext();

  if (step < 5) {
    return null
  }

  const values = watch()
  const valid = formSchema.safeParse(values)

  if (!valid.success) {
      console.log(valid.error, values)
  }

  const onCancel = () => {
    modalOpened(<CancelDialog />)
  }
  
  const onPublish = () => {
    if (valid.success) {
      publishEvent(valid.data)
    }
  }

  const onDraft = () => {
    if (valid.success) {
      draftEvent(valid.data)
    }
  }

  return (
    <>
      <div className="flex flex-row justify-between gap-2">
        <button
          className="btn btn-error"
        onClick={onCancel}
        >
          Cancel
        </button>
        <button
          className="btn"
          disabled={!valid.success || values.branchId}
          onClick={onDraft}
        >
          Draft
        </button>
        <button
          className="btn btn-primary dark:btn-info"
          disabled={!valid.success || !isReady(values)}
          onClick={onPublish}
        >
          Publish
        </button>
      </div>
    </>
  )
}

export default FinalControls

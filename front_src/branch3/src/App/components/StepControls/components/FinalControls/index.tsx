import { isReady } from "App/utils";
import { useUnit } from "effector-react"
import { useFormContext } from "react-hook-form";
import { modalOpened } from "reused/Modal/store";
import { $step } from "store/step"
import CancelDialog from "../CancelDialog";
import { formSchema } from "schema/output";
import FinalDialog from "App/components/Publish/components/FinalDialog";

const FinalControls = () => {
  const step = useUnit($step)
  const { watch } = useFormContext();

  if (step < 5) {
    return null
  }

  const values = watch()
  const valid = formSchema.safeParse(values)

  if (valid?.error) {
      console.log(valid.error, values)
  }

  const onCancel = () => {
    modalOpened(<CancelDialog />)
  }
  
  const onPublish = () => {
    if (valid?.success && valid?.data) {
      modalOpened(<FinalDialog data={valid.data} />)
    }
  }

  const onDraft = () => {
    if (valid?.success && valid?.data) {
      modalOpened(<FinalDialog data={valid.data} draft={true}/>)
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

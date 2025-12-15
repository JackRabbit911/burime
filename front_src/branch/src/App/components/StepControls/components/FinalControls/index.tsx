import { isReady } from "App/utils";
import { useUnit } from "effector-react"
import { useFormContext } from "react-hook-form";
import { modalOpened } from "reused/Modal/store";
import { $step } from "store/step"
import CancelDialog from "../CancelDialog";
import { draftSchema, finalSchema } from "schema/output";
import FinalDialog from "App/components/Publish/components/FinalDialog";
import { isObjectEmpty } from "utils";
import DeleteDialog from "./components/DeleteDialog";

const FinalControls = () => {
  const step = useUnit($step)
  const { watch, formState: { errors } } = useFormContext();

  if (step < 5) {
    return null
  }

  const values = watch()

  const onDelete = () => {
    modalOpened(<DeleteDialog id={values.draft} />)
  }

  const onCancel = () => {
    modalOpened(<CancelDialog />)
  }

  const onPublish = () => {
    const valid = finalSchema.safeParse(values)

    if (valid?.error) {
      console.log(valid.error, values)
    }

    if (valid?.success && valid?.data) {
      modalOpened(<FinalDialog data={valid.data} />)
    }
  }

  const onDraft = () => {
    const valid = draftSchema.safeParse(values)

    if (valid?.error) {
      console.log(valid.error, values)
    }

    if (valid?.success && valid?.data) {
      modalOpened(<FinalDialog data={valid.data} draft={true} />)
    }
  }

  return (
    <>
      <div className="flex flex-row justify-between gap-2">
        {values.draft ? (
          <button
            className="btn btn-error"
            onClick={onDelete}
          >
            Delete
          </button>
        ) : null}

        <button
          className="btn btn-error"
          onClick={onCancel}
        >
          Cancel
        </button>
        <button
          className="btn"
          disabled={!isObjectEmpty(errors) || values.branch.id || !Boolean(values.branch.title)}
          onClick={onDraft}

        >
          Draft
        </button>
        <button
          className="btn btn-primary dark:btn-info"
          disabled={!isObjectEmpty(errors) || !isReady(values)}
          onClick={onPublish}
        >
          Publish
        </button>
      </div>
    </>
  )
}

export default FinalControls

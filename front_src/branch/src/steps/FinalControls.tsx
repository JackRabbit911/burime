import { useUnit } from "effector-react"
import { $readyToPublish, $requiredFields } from "../store/validation"
import { modalClicked } from "../reused/Dialog/store"
import Dialog from "../reused/Dialog"
import Cancel from "./Publish/Cancel"

const FinalControls = () => {
  const { titleExists } = useUnit($requiredFields)
  const readyToPublish = useUnit($readyToPublish)

  const onPublish = () => {
    window.location.href = '/private/books'
  }

  const onCancel = () => {
    modalClicked(true)
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
          disabled={!titleExists}
        >
          Draft
        </button>
        <button
          className="btn btn-primary dark:btn-info"
          disabled={!readyToPublish}
          onClick={onPublish}
        >
          Publish
        </button>
      </div>
      <Dialog>
        <Cancel />
      </Dialog>
    </>
  )
}

export default FinalControls

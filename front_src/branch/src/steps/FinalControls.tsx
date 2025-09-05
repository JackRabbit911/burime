import { useUnit } from "effector-react"
import { $readyToPublish, $requiredFields } from "../store/validation"
import { componentAdded } from "../reused/Dialog/store"
import Cancel from "./Publish/Cancel"
import { published } from "../store/publish"

const FinalControls = () => {
  const { titleExists } = useUnit($requiredFields)
  const readyToPublish = useUnit($readyToPublish)

  const onPublish = () => {
    published()
    // window.location.href = '/private/books'
  }

  const onCancel = () => {
    componentAdded(<Cancel />)
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
    </>
  )
}

export default FinalControls

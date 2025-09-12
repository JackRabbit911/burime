import { useUnit } from "effector-react"
import { $allRight, $readyToPublish, $requiredFields, published } from "store"
import { componentAdded } from "reused/Dialog/store"
import Cancel from "./Publish/Cancel"

const FinalControls = () => {
  const { titleExists } = useUnit($requiredFields)
  const readyToPublish = useUnit($readyToPublish)
  const allRight = useUnit($allRight)

  const onPublish = () => {
    published()
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
          disabled={allRight}
        >
          Cancel
        </button>
        <button
          className="btn"
          disabled={!titleExists || allRight}
        >
          Draft
        </button>
        <button
          className="btn btn-primary dark:btn-info"
          disabled={!readyToPublish || allRight}
          onClick={onPublish}
        >
          Publish
        </button>
      </div>
    </>
  )
}

export default FinalControls

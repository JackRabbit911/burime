import { useUnit } from "effector-react"
import { $readyToPublish, $requiredFields } from "../store/validation"

const FinalControls = () => {
  const { titleExists } = useUnit($requiredFields)
  const readyToPublish = useUnit($readyToPublish)

  const onPublish = () => {
    window.location.href = '/private/books'
  }

  return (
    <>
      <div className="flex flex-row justify-between gap-2">
        <button
          className="btn btn-error"
        >
          Cansel
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

import { closeBtn, modalClosed } from "reused/Modal/store"
import { globalReset } from "store/step"

const CancelDialog = () => {
  closeBtn(false)

  const onYes = () => {
    globalReset()
    window.location.href = '/'
  }

  return (
    <>
      <h3>
        Вы уверены, что хотите прервать создание/изменение ветки?
      </h3>
      <div className="flex justify-center gap-2">
        <button className="btn" onClick={() => {modalClosed()}}>
          No
        </button>
        <button className="btn btn-primary" onClick={onYes}>
          Yes
        </button>
      </div>
    </>
  )
}

export default CancelDialog

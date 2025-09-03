import { componentRemoved } from "../../reused/Dialog/store"

const Cancel = () => {
  const onNo = () => {
    componentRemoved()
  }

  const onYes = () => {
    window.location.href = '/'
    componentRemoved()
  }

  return (
    <>
      <h3>
        Вы уверены, что хотите прервать создание/изменение ветки?
      </h3>
      <div className="flex justify-center gap-2">
        <button className="btn" onClick={onNo}>
          No
        </button>
        <button className="btn btn-primary" onClick={onYes}>
          Yes
        </button>
      </div>
    </>
  )
}

export default Cancel

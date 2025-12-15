import { closeBtn, modalClosed } from "reused/Modal/store"
import { draftDeleted } from "store/delete"

type Props = {
  id: number;
}

const DeleteDialog = ({ id }: Props) => {
  closeBtn(false)

  const onYes = (id: number) => () => {
    draftDeleted(id)
    window.location.href = '/'
  }

  return (
    <>
      <h3>
        Вы уверены, что хотите удалить ветку?
      </h3>
      <div className="flex justify-center gap-2">
        <button className="btn" onClick={() => {modalClosed()}}>
          No
        </button>
        <button className="btn btn-primary" onClick={onYes(id)}>
          Yes
        </button>
      </div>
    </>
  )
}

export default DeleteDialog

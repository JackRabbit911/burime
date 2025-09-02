import { useUnit } from "effector-react";
import { $isOpen, modalClicked } from "./store";

type Props = {
  children?: React.ReactNode
}

const Dialog = ({ children }: Props) => {
  const isOpen = useUnit($isOpen);

  const onClose = () => {
    modalClicked(false)
  }

  return (
    <dialog id="my_modal" className="modal" open={isOpen}>
      <div className="modal-box">
        <button
          className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
          onClick={onClose}
        >
          ✕
        </button>
        {children}
      </div>
    </dialog>
  );
};

export default Dialog

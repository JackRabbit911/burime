import { useUnit } from "effector-react";
import { $closeBtn, $modalComponent, modalClosed } from "./store";

const Modal = () => {
  const closeBtn = useUnit($closeBtn)
  const component = useUnit($modalComponent)
  const isOpen = Boolean(component);

  return (
    <dialog id="my_modal" className="modal" open={isOpen}>
      <div className="modal-box">
        {closeBtn ? (
          <button
            className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
            onClick={() => {modalClosed()}}
          >
            ✕
          </button>
        ) : null
        }
        {component}
      </div>
    </dialog>
  );
};

export default Modal

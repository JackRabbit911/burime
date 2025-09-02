import { useUnit } from "effector-react";
import { $component, componentRemoved } from "./store";

const Dialog = () => {
  const component = useUnit($component)
  const isOpen = Boolean(component);

  const onClose = () => {
    componentRemoved()
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
        {component}
      </div>
    </dialog>
  );
};

export default Dialog

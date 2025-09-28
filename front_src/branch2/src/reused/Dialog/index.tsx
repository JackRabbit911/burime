import { useUnit } from "effector-react";

import { $message, messageRemoved } from "store/messages";
import Actions from "./Actions";

const Dialog = () => {
  const message = useUnit($message);

  if (!message) {
    return null;
  }

  const onClose = () => {
    messageRemoved();
  };

  const {
    title,
    actions,
    component,
    description,
    hideCloseButton,
  } = message;

  return (
    <dialog id="my_modal" className="modal" open>
      <div className="modal-box">
        {hideCloseButton ? null : (
          <button
            className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
            onClick={onClose}
          >
            ✕
          </button>
        )}
        {!title ? null : <h3>{title}</h3>}
        {!description ? null : <p>{description}</p>} 
        {!component ? null : component}
        <Actions actions={actions} onClose={onClose} />
      </div>
    </dialog>
  );
};

export default Dialog;

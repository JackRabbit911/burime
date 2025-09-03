    // Example using state to control modal-open class
    import React, { useState } from 'react';

    const Modal: React.FC = () => {
      const [isModalOpen, setIsModalOpen] = useState(false);

      const openModal = () => {
        setIsModalOpen(true);
        document.body.classList.add('modal-open'); // Add class to body
      };

      const closeModal = () => {
        setIsModalOpen(false);
        document.body.classList.remove('modal-open'); // Remove class from body
      };

      return (
        <>
          <button onClick={openModal}>Open Modal</button>
          {isModalOpen && (
            <dialog id="my_modal_8" className="modal" open>
              <div className="modal-box">
                <h3 className="font-bold text-lg">Hello!</h3>
                <p className="py-4">Press ESC key or click the button below to close</p>
                <div className="modal-action">
                  <form method="dialog">
                    <button className="btn" onClick={closeModal}>Close</button>
                  </form>
                </div>
              </div>
            </dialog>
          )}
        </>
      );
    };

export default Modal

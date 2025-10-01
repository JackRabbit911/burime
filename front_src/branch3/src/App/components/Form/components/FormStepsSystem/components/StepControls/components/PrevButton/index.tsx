import { useUnit } from "effector-react";
import { $step, stepChanged } from "store/common";

const PrevButton = () => {
  const step = useUnit($step);

  const onPrevStep = () => {
    stepChanged(step - 1);
  }

  return (
    <button
      onClick={onPrevStep}
      disabled={step === 1}
      className="btn btn-primary dark:btn-info"
    >
      Prev
    </button>
  );
};

export default PrevButton;

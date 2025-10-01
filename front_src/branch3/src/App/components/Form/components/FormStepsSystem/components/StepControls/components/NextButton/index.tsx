import { useUnit } from "effector-react";

import { $step, stepChanged } from "store/common";

const NextButton = () => {
  const step = useUnit($step);

  const onNextStep = () => {
    stepChanged(step + 1);
  }

  return step >= 5 ? null : (
    <button
      className="btn btn-primary dark:btn-info"
      onClick={onNextStep}
    >
      Next
    </button>
  );
};

export default NextButton;

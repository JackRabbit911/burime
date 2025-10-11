import { useUnit } from "effector-react";
import { useFormContext } from "react-hook-form";
import { $step, stepChanged } from "store/step";

const NextButton = () => {
  const step = useUnit($step);

  const onNextStep = () => {
    stepChanged(step + 1);
  }

  const { formState: { isValid } } = useFormContext();

  return step >= 5 ? null : (
    <button
      onClick={onNextStep}
      disabled={!isValid}
      className="btn btn-primary dark:btn-info"
    >
      Next
    </button>
  );
};

export default NextButton;

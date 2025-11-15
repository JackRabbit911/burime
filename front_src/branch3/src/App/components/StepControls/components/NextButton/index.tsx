import { useUnit } from "effector-react";
import { useFormContext } from "react-hook-form";
import { $step, stepChanged } from "store/step";
import { isObjectEmpty } from "utils";

const NextButton = () => {
  const step = useUnit($step);

  const onNextStep = () => {
    stepChanged(step + 1);
  }

  const { formState: { errors } } = useFormContext();

  return step >= 5 ? null : (
    <button
      onClick={onNextStep}
      disabled={!isObjectEmpty(errors)}
      className="btn btn-primary dark:btn-info"
    >
      Next
    </button>
  );
};

export default NextButton;

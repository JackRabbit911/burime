import { useUnit } from "effector-react";
import { useFormContext } from "react-hook-form";
import { $step, stepChanged } from "store/step";
import { isObjectEmpty } from "utils";

const PrevButton = () => {
  const step = useUnit($step);

  const onPrevStep = () => {
    stepChanged(step - 1);
  }

  const { formState: { errors } } = useFormContext();

  return (
    <button
      onClick={onPrevStep}
      disabled={step === 1 || !isObjectEmpty(errors)}
      className="btn btn-primary dark:btn-info"
    >
      Prev
    </button>
  );
};

export default PrevButton;

import { useUnit } from "effector-react";
import { $step, stepChanged } from "store/common";
import { $darkMode } from "store/colorScheme";

type Props = {
  step: number;
  title: string;
  isError?: boolean;
};

const Step = ({ step, title, isError = false }: Props) => {
  const [currentStep, darkMode] = useUnit([$step, $darkMode]);
  const color = darkMode ? 'step step-info' : 'step step-primary';
  const liClassName = currentStep >= step ? color : 'step';

  const btnClassName =
    isError ?
    'btn btn-link text-error' :
    'btn btn-link dark:text-info';

  const onStep = (newStep: number) => () => {
    stepChanged(newStep);
  }

  return (
    <li className={liClassName}>
      <button className={btnClassName} onClick={onStep(step)}>
        {title}
      </button>
    </li>
  );
};

export default Step;

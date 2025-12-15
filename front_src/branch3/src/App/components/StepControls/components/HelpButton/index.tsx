import Helper from "App/components/Help";
import { helpBtnClicked } from "App/components/Help/store";
import { useUnit } from "effector-react";
import { modalOpened } from "reused/Modal/store";
import { $step } from "store/step";

const HelpButton = () => {
  const step = useUnit($step);

  const onClick = (step: number) => () => {
    helpBtnClicked(step)
    modalOpened(<Helper step={step} />)
  }

  return step >= 5 ? null : (
    <button
      className="btn btn-circle btn-success text-2xl"
      onClick={onClick(step)}
    >
      ?
    </button>
  );
};

export default HelpButton;

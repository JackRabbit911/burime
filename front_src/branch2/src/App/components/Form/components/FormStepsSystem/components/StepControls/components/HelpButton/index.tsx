import { useUnit } from "effector-react";

import { $step } from "store/common";
import { messageAdded } from "store";
import Helper from "./Helper";

const HelpButton = () => {
  const step = useUnit($step);

  const onShowHelp = () => {
    messageAdded({
      component: <Helper />,
      actions: [{ label: 'Закрыть' }],
    });
  };

  return step >= 5 ? null : (
    <button
      onClick={onShowHelp}
      className="btn btn-circle btn-success text-2xl"
    >
      ?
    </button>
  );
};

export default HelpButton;

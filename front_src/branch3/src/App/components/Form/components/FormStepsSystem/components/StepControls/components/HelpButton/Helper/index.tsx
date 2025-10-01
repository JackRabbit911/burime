import { useEffect } from "react";

import { useUnit } from "effector-react";

import { $hepls, $step, helpRequested } from "store";
import OptimisticAwaitingMessage from "./OptimisticAwaitingMessage";
import MarkDown from "reused/MarkDown";

const Helper = () => {
  const [step, helps] = useUnit([$step, $hepls]);
  const help = helps.find((help) => help.step === step)?.body || '';

  useEffect(
    () => {
      if (!help) {
        helpRequested(step);
      }
    },
    [help, step],
  );

  return !help ? (
    <OptimisticAwaitingMessage />
  ) : (
    <MarkDown text={help} />
  );
};

export default Helper;

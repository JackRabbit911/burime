import { useUnit } from "effector-react";
import type { Help } from "./schema";
import { $hepls } from "./store";
// import type { Help } from "../../store/help/types";
// import { $hepls } from "../../store/help";

type Props = {
  step: number;
}

const getHelp = (helps: Help[], step: number) =>
  helps.find((help: Help) => help.step === step)?.body || ''

const Helper = ({ step }: Props) => {
  const help = getHelp(useUnit($hepls), step)

  return !help ? (
    <div className="flex flex-col justify-center min-h-64">
      <div className="text-center w-full">
        <span className="text-xl">
          Ждите, помощь идёт
        </span>
        <span className="loading loading-dots loading-xs ms-1.5 mt-2.5"></span>
      </div>
    </div>
  ) : (
    <div className="markdown" dangerouslySetInnerHTML={{ __html: help }} />
  )
}

export default Helper

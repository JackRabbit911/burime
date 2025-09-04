import { useUnit } from "effector-react";
import type { Help } from "../../store/help/types";
import { $hepls } from "../../store/help";

type Props = {
    step: number;
}

const getHelp = (helps: Help[], step: number) =>
    helps.find((help: Help) => help.step === step)?.body || 'huy'

const Helper = ({ step }: Props) => {
    const help = getHelp(useUnit($hepls), step)

    return !help ? <h1>Ничего нет</h1> : (
       <div className="markdown" dangerouslySetInnerHTML={{ __html: help}} />
    )
}

export default Helper

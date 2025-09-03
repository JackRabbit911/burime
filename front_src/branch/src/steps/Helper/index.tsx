import { helpContents } from "./constants";

type Props = {
    step: number;
}

const Helper = ({ step }: Props) => {
    const help = helpContents.find(([id]) => id === step)

    return !help ? <h1>Ничего нет</h1> : (
        <div>
            <div>
                {help[0]}
            </div>
            <div>
                {help[1]}
            </div>
            <div>
                {help[2]}
            </div>
        </div>
    )
}

export default Helper

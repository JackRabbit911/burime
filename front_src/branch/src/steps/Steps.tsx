import { useUnit } from "effector-react";
import Step from "../reused/Step"
import { $requiredFields } from "../store/validation";

type Props = {
  step: number;
}

const Steps = ({ step }: Props) => {
  const { authorExists, genresExists } = useUnit($requiredFields)

  return (
    <ul className="steps w-full mb-4 mt-1">
      <Step
        step={1}
        title="Genres"
        isError={!genresExists && step > 1}
      />
      <Step
        step={2}
        title="Rules"
      />
      <Step
        step={3}
        title="Authors"
        isError={!authorExists && step > 3}
      />
      <Step
        step={4}
        title="Cover"
      />
      <Step
        step={5}
        title="Publish"
      />
    </ul>
  )
}

export default Steps

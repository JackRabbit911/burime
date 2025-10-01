import { useUnit } from "effector-react";

import Step from "./Step";
import { $requiredFields } from "store/validation";
import { $step } from "store";
import { useFormContext } from "react-hook-form";

const Steps = () => {
  const [step, { authorExists }] = useUnit([$step, $requiredFields]);
  const { formState: { errors }} = useFormContext();
  const isGenresError = Boolean(errors?.['genres']) && step > 1;
  const isAuthorsError = !authorExists && step > 3;

  return (
    <ul className="steps w-full my-4">
      <Step step={1} title="Genres" isError={isGenresError} />
      <Step step={2} title="Rules" />
      <Step step={3} title="Authors" isError={isAuthorsError} />
      <Step step={4} title="Cover" />
      <Step step={5} title="Publish" />
    </ul>
  );
};

export default Steps;

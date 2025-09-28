import { useUnit } from "effector-react";
import { useFormContext } from "react-hook-form";
import { ErrorMessage } from "@hookform/error-message";

import GenreCheckBox from "reused/reactHookForms/GenreCheckBox";
import { $sameWeightGenres, $step } from "store";

const Genres = () => {
  const [step, sameWeightGenres] = useUnit([$step, $sameWeightGenres]);
  const { formState: { errors } } = useFormContext();

  return step !== 1 ? null : (
    <fieldset className="fieldset">
      <legend className="fieldset-legend flex justify-end w-full h-8">
        {!errors?.['genres'] ? null : (
          <ErrorMessage
            as="span"
            name="genres"
            errors={errors}
            className="label-text text-error"
          />
        )}
      </legend>
      {sameWeightGenres.map(
        ({ genres }, key0) => (
          <div className="flex flex-row flex-wrap gap-4" key={key0}>
            {!key0 ? null : <div className="divider w-full my-0"></div>}
            {genres.map(({ title }, key1) => (
              <GenreCheckBox
                key={key1}
                label={title}
                fieldName={`genres[${key0}][${key1}]`}
              />
            ))}
          </div>
        )
      )}
    </fieldset>
  );
};

export default Genres;

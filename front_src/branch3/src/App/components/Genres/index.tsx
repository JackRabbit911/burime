import { type TotalGenres } from "mock/genres";
import { useFormContext } from "react-hook-form";
import SameWeightGenres from "./SameWeightGenres";
import { useUnit } from "effector-react";
import { $step } from "store/step";

type Props = {
  genres: TotalGenres[];
  checked: number[];
  fieldName?: string;
}

const Genres = ({ genres, checked, fieldName = 'genres' }: Props) => {
  const { getValues, formState: { errors } } = useFormContext();
  const step = useUnit($step)

  checked = getValues(fieldName) || checked

  return step === 1 ? (
    <fieldset className="fieldset">
      <legend className="fieldset-legend my-3">{"Genres"}</legend>
      {genres.map((group, key) => (
        <div key={key}>
          {key as number > 0 ? <div className="divider w-full my-0"></div> : null}
          <div className="flex flex-wrap gap-3">
            <SameWeightGenres
              genres={group}
              checked={checked}
            />
          </div>
        </div>
      ))}
      <div className="fieldset-label text-error">
        {errors[fieldName]?.message as string}
      </div>
    </fieldset>
  ) : null;
};

export default Genres;

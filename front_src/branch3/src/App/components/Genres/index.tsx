import { useFormContext } from "react-hook-form";
import SameWeightGenres from "./components/SameWeightGenres";
import { t } from "i18n/utils";
import type { Genre } from "schema/input";
import { getObjectProp } from "utils";

type Props = {
  genres: Genre[];
  checked: number[];
  fieldName?: string;
}

const Genres = ({ genres, checked, fieldName = 'branch_genres' }: Props) => {
  const { getValues, formState: { errors } } = useFormContext();
  const err = getObjectProp(errors, fieldName)
  
  checked = getValues(fieldName) || checked

  return (
    <fieldset className="fieldset">
      <legend className="fieldset-legend my-3">{t("Genres")}</legend>
      {genres.map((group, key) => (
        <div key={key}>
          {key as number > 0 ? <div className="divider w-full my-0"></div> : null}
          <div className="flex flex-wrap gap-4">
            <SameWeightGenres
              genres={group}
              checked={checked}
            />
          </div>
        </div>
      ))}
      <div className="fieldset-label text-error">
        {err?.message as string}
      </div>
    </fieldset>
  )
};

export default Genres;

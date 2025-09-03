import { useUnit } from "effector-react"
import { $selectedGenres, genreToggled } from "../store/branch"
import CheckBox from "../reused/CheckBox"
import { $sameWeightGenres } from "../store/bootstrap"
import { $requiredFields } from "../store/validation"

const Genres = () => {
  const sameWeightGenres = useUnit($sameWeightGenres)
  const selectedGenres = useUnit($selectedGenres)
  const { genresExists } = useUnit($requiredFields)
  const alert = genresExists ? '' : 'You need to choose at least one genre'

  return (
    <fieldset className="fieldset">
      <legend className="fieldset-legend flex justify-end w-full h-8">
        {alert && <span className="label-text text-error">
          {alert}
        </span>}
      </legend>
      {sameWeightGenres.map(
        ({ genres }, key) => (
          <div className="flex flex-row flex-wrap gap-4" key={key}>
            {key > 0 && (
              <div className="divider w-full my-0"></div>
            )}
            {genres.map(({ id, title }) => (
              <CheckBox
                label={title}
                value={id}
                checked={selectedGenres.includes(id)}
                onChange={genreToggled}
              />
            ))}
          </div>
        )
      )}
    </fieldset>
  )
}

export default Genres

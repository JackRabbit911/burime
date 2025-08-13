import { useUnit } from "effector-react"
import { $sameWeightGenres } from "../store/vocabularies"
import { $selectedGenres, genreToggled } from "../store/branch"

const Genres = () => {
  const sameWeightGenres = useUnit($sameWeightGenres)
  const selectedGenres = useUnit($selectedGenres)

  return (
    <fieldset className="fieldset">

        {sameWeightGenres.map(
          ({ genres }, key) => (
            <div className="flex flex-row flex-wrap gap-4" key={key}>
              {key > 0 && (
                <div className="divider w-full my-0"></div>
              )}
              {genres.map(({ id, title }) => (
                <label className="fieldset-label flex justify-between">
                  <legend className="fieldset-legend me-0.5 pb-1 pt-0">{title}</legend>
                  <input
                    type="checkbox"
                    checked={selectedGenres.includes(id)}
                    className="checkbox"
                    onChange={() => {
                      genreToggled(id)
                    }}
                  />
                </label>
              ))}
            </div>
          )
        )}

    </fieldset>
  )
}

export default Genres

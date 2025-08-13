import { useUnit } from "effector-react"
import { $sameWeightGenres } from "../store/vocabularies"
import { $selectedGenres, genreToggled } from "../store/branch"
import CheckBox from "../reused/CheckBox"

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

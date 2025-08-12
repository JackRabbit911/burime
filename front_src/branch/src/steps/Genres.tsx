import { useUnit } from "effector-react"
import { $genresList } from "../store/vocabularies"

const Genres = () => {
  const genres = useUnit($genresList)

  return (
    <div className="flex flex-wrap gap-2">
      {genres.map(
        (item) => (
          <div>{item.title}</div>
        )
      )}
    </div>
  )
}

export default Genres

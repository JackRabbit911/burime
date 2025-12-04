// import type { TotalGenres } from "mock/genres";
import GenreCheckBox from "App/components/Genres/components/GenreCheckBox";
import type { Genre } from "schema/input";

type Props = {
  genres: Genre;
  checked: number[];
  fieldName?: string;
}

const SameWeightGenres = ({ genres, checked, fieldName = 'genres' }: Props) => {
  return (
    genres.map((option) => (
      <GenreCheckBox
        key={option.id}
        fieldName={fieldName}
        label={option.title}
        value={option.id}
        checked={checked}
      />
    ))
  )
}

export default SameWeightGenres

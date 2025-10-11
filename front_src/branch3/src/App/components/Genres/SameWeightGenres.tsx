import type { TotalGenres } from "mock/genres";
import GenreCheckBox from "App/components/Genres/GenreCheckBox";

type Props = {
  genres: TotalGenres;
  checked: number[];
  fieldName?: string;
}

const SameWeightGenres = ({ genres, checked, fieldName = 'genres' }: Props) => {
  return (
    genres.map((option, key) => (
      <GenreCheckBox
        key={key}
        fieldName={fieldName}
        label={option.title}
        value={option.id}
        checked={checked}
      />
    ))
  )
}

export default SameWeightGenres

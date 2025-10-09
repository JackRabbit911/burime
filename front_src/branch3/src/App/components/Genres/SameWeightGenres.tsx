import type { TotalGenres } from "mock/genres";
import CheckBox from "reused/CheckBox";

type Props = {
  genres: TotalGenres;
  checked: number[];
  fieldName?: string;
}

const SameWeightGenres = ({ genres, checked, fieldName = 'genres' }: Props) => {
  return (
    genres.map((option, key) => (
      <CheckBox
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

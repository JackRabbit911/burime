import { useUnit } from "effector-react";
import type { BranchAuthor } from "../../store/authors/types"
import { $sameWeightGenres } from "../../store/bootstrap";
import { getGenreString } from "./utils";
import type { Info } from "../../store/bootstrap/types";
import { getMasterAlias } from "../../store/validation/utils";

type Props = {
  authors: BranchAuthor[];
  genres: number[];
  title: string | null;
  info: Info;
}

const Inscriptions = ({ authors, genres, title, info }: Props) => {
  const totalGenres = useUnit($sameWeightGenres)
  const authorName = getMasterAlias(authors)
  const genreStr = getGenreString(totalGenres, genres)

  return (
    <div
      className="flex flex-col justify-between text-center shadow overflow-hidden w-full h-full"
      style={{
        color: info.text_color,
      }}
    >
      <div className="z-20" style={{ fontSize: '6cqw' }}>
        {authorName}
      </div>
      <div
        className="z-20"
        style={{
          fontSize: `${info.text_size}cqw`,
          lineHeight: 'normal',
        }}
      >
        {title}
      </div>
      <div
        className="z-20"
        style={{ fontSize: '5cqw' }}>
        {genreStr}
      </div>
    </div>
  )
}

export default Inscriptions

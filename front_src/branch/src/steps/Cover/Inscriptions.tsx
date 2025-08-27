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
  width: number;
}

const Inscriptions = ({ authors, genres, title, info, width }: Props) => {
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
      <div className="z-20" style={{ fontSize: `${width / 17}px` }}>
        {authorName}
      </div>
      <div
        className="z-20"
        style={{
          fontSize: `${width * info.text_size / 200}px`,
          lineHeight: 'normal',
        }}
      >
        {title}
      </div>
      <div
        className="z-20"
        style={{ fontSize: `${width / 22}px` }}>
        {genreStr}
      </div>
    </div>
  )
}

export default Inscriptions

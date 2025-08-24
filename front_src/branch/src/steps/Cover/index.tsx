import { useUnit } from "effector-react"
import { $branch } from "../../store/branch"
import type { CSSProperties } from "react"
import { $sameWeightGenres } from "../../store/bootstrap"
import { getGenreString, getMasterAlias } from "./utils"

const Cover = () => {
  const {authors, genres, title, info} = useUnit($branch)
  const totalGenres = useUnit($sameWeightGenres)

  const authorName = getMasterAlias(authors)
  const genreStr = getGenreString(totalGenres, genres)

  const style: CSSProperties = {
    background: info.bg_color,
    color: info.text_color,
  };

  return (
    <div className="grid md:grid-cols-3 gap-4">
      <div className="border border-neutral-content bg-cover aspect-2/3 p-2
        flex flex-col justify-between text-center shadow overflow-hidden" 
        style={style}
      >
        <div className="text-sm">
          {authorName}
        </div>
        <div className="text-2xl">
          {title}
        </div>
        <div className="text-xs">
          {genreStr}
        </div>
      </div>
      <fieldset className="md:col-span-2">
        Form
      </fieldset>
    </div>
  )
}

export default Cover

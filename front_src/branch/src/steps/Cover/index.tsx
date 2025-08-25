import { useUnit } from "effector-react"
import { $branch, bgColorChanged, coverFileChanged, textColorChanged } from "../../store/branch"
import type { CSSProperties } from "react"
import { $sameWeightGenres } from "../../store/bootstrap"
import { getGenreString, getMasterAlias } from "./utils"
import ColorPicker from "../../reused/ColorPicker"
import FileInput from "../../reused/FileInput"

const Cover = () => {
  const { authors, genres, title, info } = useUnit($branch)
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
      <fieldset className="fieldset md:col-span-2">
        <legend className="fieldset-legend mb-3">
          Choose colors
        </legend>
        <div className="flex flex-row justify-around">
          <ColorPicker
            label="Background"
            value={info.bg_color}
            onChange={bgColorChanged}
          />
          <ColorPicker
            label="Text"
            value={info.text_color}
            onChange={textColorChanged}
          />
        </div>
        <div className="divider my-8 text-lg text-current/75">or</div>
        <FileInput
          label="Cover image"
          value={info.cover}
          optional="Up to 2Mb"
          onChange={coverFileChanged}
        />
      </fieldset>
    </div>
  )
}

export default Cover

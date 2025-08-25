import { useUnit } from "effector-react"
import {
  $branch,
  bgColorChanged,
  coverFileChanged,
  textColorChanged,
  textSizeChanged
} from "../../store/branch"
import { useEffect, useRef, useState } from "react"
import { $sameWeightGenres } from "../../store/bootstrap"
import { getGenreString, getMasterAlias } from "./utils"
import ColorPicker from "../../reused/ColorPicker"
import FileInput from "../../reused/FileInput"
import Range from "../../reused/Range"

const Cover = () => {
  const { authors, genres, title, info } = useUnit($branch)
  const totalGenres = useUnit($sameWeightGenres)

  const authorName = getMasterAlias(authors)
  const genreStr = getGenreString(totalGenres, genres)

  const coverRef = useRef<HTMLDivElement>(null)
  const [width, setWidth] = useState<number>(0);

  useEffect(() => {
    const handleResize = () => {
      if (coverRef.current) {
        setWidth(coverRef.current.offsetWidth)
      }
    };

    handleResize()
    window.addEventListener('resize', handleResize);

    return () => {
      window.removeEventListener('resize', handleResize);
    };
  }, []);

  return (
    <div className="grid md:grid-cols-3 gap-4">
      <div
        id="cover"
        ref={coverRef}
        className="border border-neutral-content bg-cover aspect-2/3 p-2
        flex flex-col justify-between text-center shadow overflow-hidden"
        style={{
          background: info.bg_color,
          color: info.text_color,
        }}
      >
        <div style={{fontSize: `${width/17}px`}}>
          {authorName}
        </div>
        <div
          style={{
            fontSize: `${width * info.text_size / 200}px`,
            lineHeight: 'normal',
          }}
        >
          {title}
        </div>
        <div style={{fontSize: `${width/22}px`}}>
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
        <Range
          min={10}
          max={50}
          step={1}
          value={info.text_size}
          label="Font size"
          onChange={textSizeChanged}
        />
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

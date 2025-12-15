import { useUnit } from "effector-react"
import { useFormContext } from "react-hook-form";
import { $bootstrap } from "store/bootstrap";
import { fileToUrl, getGenreString } from "../utils";
import { getMasterAlias } from "App/utils";

const BgLayers = () => {
  const bootstrap = useUnit($bootstrap)
  const { getValues, watch, formState: { errors } } = useFormContext()

  const ownAuthors = bootstrap?.ownAuthors || []
  const masterId = getValues('masterId')

  const authorName = getMasterAlias(ownAuthors, masterId)
  const title = watch('branch.title')
  const textSize = watch('branch.info.text_size')
  const textColor = watch('branch.info.text_color')
  const bgColor = watch('branch.info.bg_color')
  const bgImg = errors.bgImg ? null : watch('bgImg')
  const cover = errors.cover ? null : watch('cover')
  const bgUrl = fileToUrl(bgImg)
  const coverUrl = fileToUrl(cover)
  const branchGenres = getValues('branch_genres')
  const totalGenres = bootstrap?.total_genres || []
  const genreStr = getGenreString(totalGenres, branchGenres)

  return (
    <>
      <div
        className="absolute top-0 left-0 w-full h-full"
        style={{ backgroundColor: bgColor }}
      ></div>
      {Boolean(bgImg) &&
        <div
          className="absolute top-0 left-0 w-full h-full bg-cover bg-center"
          style={{ backgroundImage: `url(${bgUrl})` }}
        ></div>
      }
      <div
        className="absolute top-0 left-0 flex flex-col justify-between text-center shadow overflow-hidden w-full h-full"
        style={{ color: textColor}}
      >
        <div style={{ fontSize: '6cqw' }}>
          {authorName}
        </div>
        <div
          style={{
            fontSize: `${textSize}cqw`,
            lineHeight: 'normal',
          }}
        >
          {title}
        </div>
        <div
          style={{ fontSize: '5cqw' }}
        >
          {genreStr}
        </div>
      </div>
      {Boolean(cover) &&
        <div
          className="absolute top-0 left-0 w-full h-full z-30"
        >
          <img src={coverUrl} className="w-full h-full" />
        </div>
      }
    </>
  )
}

export default BgLayers

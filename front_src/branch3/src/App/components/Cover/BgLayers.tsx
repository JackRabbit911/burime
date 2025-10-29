import { useUnit } from "effector-react"
import { useFormContext } from "react-hook-form";
import { $bootstrap } from "store/bootstrap";
import { coverFileToUrl, getGenreString, getMasterAlias } from "./utils";

const BgLayers = () => {
  const bootstrap = useUnit($bootstrap)
  const { getValues } = useFormContext()

  const ownAuthors = bootstrap?.ownAuthors || []
  const masterId = getValues('masterId')

  const authorName = getMasterAlias(ownAuthors, masterId)
  const title = getValues('branchTitle')
  const textSize = getValues('text_size')
  const textColor = getValues('text_color')
  const bgColor = getValues('bg_color')
  const { bg_img, cover } = getValues('files')
  const bgUrl = coverFileToUrl(bg_img)
  const coverUrl = coverFileToUrl(cover)
  const branchGenres = getValues('genres')
  const totalGenres = bootstrap?.genres || []
  const genreStr = getGenreString(totalGenres, branchGenres)

  return (
    <>
      <div
        className="absolute top-0 left-0 w-full h-full"
        style={{ backgroundColor: bgColor }}
      ></div>
      {Boolean(bg_img) &&
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

import { useUnit } from "effector-react"
import { $branch } from "../../store/branch"
import CoverControls from "./CoverControls"
import { getMasterAlias } from "./utils"
import Alert from "./Alert"
import CoverWrapper from "./CoverWrapper"

const Cover = () => {
  const { authors, genres, title, info } = useUnit($branch)

  const author = getMasterAlias(authors)

  const authorExists = typeof author === 'undefined' ? false : true
  const genresExists = genres.length === 0 ? false : true
  const titleExists = !title ? false : true
  
  return ( authorExists && genresExists && titleExists )
  ? (
    <div className="grid md:grid-cols-3 gap-4">
      <CoverWrapper
        authors={authors}
        genres={genres}
        title={title}
        info={info}
      />
      <CoverControls info={info} />
    </div>
  ) : <Alert
      authorExists={authorExists}
      genresExists={genresExists}
      titleExists={titleExists}
    />
}

export default Cover

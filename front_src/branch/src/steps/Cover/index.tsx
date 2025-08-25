import { useUnit } from "effector-react"
import { $branch } from "../../store/branch"
import BookCover from "./BookCover"
import CoverControls from "./CoverControls"

const Cover = () => {
  const { authors, genres, title, info } = useUnit($branch)
  
  return (
    <div className="grid md:grid-cols-3 gap-4">
      <BookCover
        authors={authors}
        genres={genres}
        title={title}
        info={info}
      />
      <CoverControls info={info} />
    </div>
  )
}

export default Cover

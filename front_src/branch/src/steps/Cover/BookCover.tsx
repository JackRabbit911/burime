import { useUnit } from "effector-react";
import { useEffect, useRef, useState } from "react";
import { getGenreString, getMasterAlias } from "./utils";
import { $sameWeightGenres } from "../../store/bootstrap";
import type { BranchAuthor } from "../../store/authors/types";
import type { Info } from "../../store/bootstrap/types";

type Props = {
  authors: BranchAuthor[];
  genres: number[];
  title: string | null;
  info: Info;
}

const BookCover = ({ authors, genres, title, info }: Props) => {
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
  }, [info]);

  return (
    <div
      ref={coverRef}
      className="border border-neutral-content bg-cover aspect-2/3 p-2
        flex flex-col justify-between text-center shadow overflow-hidden"
      style={{
        background: info.bg_color,
        color: info.text_color,
      }}
    >
      <div style={{ fontSize: `${width / 17}px` }}>
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
      <div style={{ fontSize: `${width / 22}px` }}>
        {genreStr}
      </div>
    </div>
  )
}

export default BookCover

import { useUnit } from "effector-react";
import { useEffect, useRef, useState } from "react";
import { getGenreString, getMasterAlias } from "./utils";
import { $sameWeightGenres } from "../../store/bootstrap";
import type { BranchAuthor } from "../../store/authors/types";
import type { Info } from "../../store/bootstrap/types";
import { $coverFile, $coverUrl } from "../../store/common";

type Props = {
  authors: BranchAuthor[];
  genres: number[];
  title: string | null;
  info: Info;
}

const CoverWrapper = ({ authors, genres, title, info }: Props) => {
  const totalGenres = useUnit($sameWeightGenres)
  const authorName = getMasterAlias(authors)
  const genreStr = getGenreString(totalGenres, genres)

  const coverUrl = useUnit($coverUrl)
  const coverFile = useUnit($coverFile)

  console.log(coverFile)

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
      className="relative border border-neutral-content bg-cover bg-center aspect-2/3"
      ref={coverRef}
    >
      <div
        className="absolute top-0 left-0 w-full h-full"
        style={{ backgroundColor: info.bg_color, }}
      ></div>
      {info.bg_img &&
        <div
          className="absolute top-0 left-0 w-full h-full z-10"
          style={{ backgroundColor: "yellow" }}
        ></div>
      }
      {info.cover &&
        <div
          className="absolute top-0 left-0 w-full h-full z-30"
          style={{ backgroundColor: "yellow" }}
        >
          <img src={coverUrl} className="w-full h-full" />
        </div>
      }
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
    </div>
  )
}

export default CoverWrapper

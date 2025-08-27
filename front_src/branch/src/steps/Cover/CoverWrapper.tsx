import { useEffect, useRef, useState } from "react";
import type { BranchAuthor } from "../../store/authors/types";
import type { Info } from "../../store/bootstrap/types";
import Inscriptions from "./Inscriptions";
import BgLayers from "./BgLayers";

type Props = {
  authors: BranchAuthor[];
  genres: number[];
  title: string | null;
  info: Info;
}

const CoverWrapper = ({ authors, genres, title, info }: Props) => {
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
      <BgLayers info={info} />
      <Inscriptions
        authors={authors}
        genres={genres}
        title={title}
        info={info}
        width={width}
      />
    </div>
  )
}

export default CoverWrapper

import { useUnit } from "effector-react"
import type { Info } from "../../store/bootstrap/types"
import { $coverUrl } from "../../store/common"

type Props = {
  info: Info;
}

const BgLayers = ({ info }: Props) => {
  const coverUrl = useUnit($coverUrl)

  return (
    <>
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
        >
          <img src={coverUrl} className="w-full h-full" />
        </div>
      }
    </>
  )
}

export default BgLayers

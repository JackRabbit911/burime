import Inscriptions from "./Inscriptions";
import BgLayers from "./BgLayers";
import { useUnit } from "effector-react";
import { $branch } from "../../store/branch";
import { $readyToPublish } from "../../store/validation";
import CoverControls from "./CoverControls";

const CoverWrapper = () => {
  const { authors, genres, title, info } = useUnit($branch)
  const readyToPublish = useUnit($readyToPublish)

  if (!readyToPublish) {
    return null
  }

  return (
    <div className="grid md:grid-cols-3 gap-4">
      <div className="relative border border-neutral-content bg-cover bg-center aspect-2/3 inline-size">
        <BgLayers info={info} />
        <Inscriptions
          authors={authors}
          genres={genres}
          title={title}
          info={info}
        />
      </div>
      <CoverControls info={info} />
     </div>
  )
}

export default CoverWrapper

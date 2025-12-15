import BgLayers from "./BgLayers"
import CoverControls from "./CoverControls"

const CoverWrapper = () => {

  // const { authors, genres, title, info } = useUnit($branch)
  // const readyToPublish = useUnit($readyToPublish)

  // if (!readyToPublish) {
  //   return null
  // }

  return (
    <div className="grid md:grid-cols-3 gap-4">
      <div className="relative border border-neutral-content bg-cover bg-center aspect-2/3 inline-size">
        <BgLayers />
      </div>
      {/* <div>controls</div> */}
      <CoverControls />
     </div>
  )
}

export default CoverWrapper

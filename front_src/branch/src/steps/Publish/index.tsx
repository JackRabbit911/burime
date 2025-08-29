import FirstLastPost from "./FirstLastPost"
import Indicator from "./Indicator"

const Publish = () => {
  return (
    <div className="grid md:grid-cols-3 gap-4">
      <Indicator />
      <FirstLastPost />
    </div>
  )
}

export default Publish

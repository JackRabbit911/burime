import FirstLastPost from "./FirstLastPost";
import Indicator from "./Indicator";

const Publish = () => {
  return (
    <fieldset className="fieldset">
      <div className="grid md:grid-cols-3 gap-4">
        <div>
          <Indicator />
        </div>
        <div className="md:col-span-2">
          <FirstLastPost />
        </div>
      </div>
    </fieldset>
  )
}

export default Publish

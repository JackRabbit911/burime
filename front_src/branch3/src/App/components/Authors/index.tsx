import type { Bootstrap } from "schema/input"
import AuthorsWrapper from "./AuthorsWrapper"

type Props = {
  bootstrap: Bootstrap;
}

const Authors = ({ bootstrap }: Props) => {

  return (
    <div className="grid md:grid-cols-3 gap-4">
      <AuthorsWrapper bootstrap={bootstrap} />
    </div>
  )
}

export default Authors

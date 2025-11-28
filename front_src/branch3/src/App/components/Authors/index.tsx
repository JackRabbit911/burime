import type { Bootstrap } from "schema/input"
import AuthorsWrapper from "./AuthorsWrapper"
import { useUnit } from "effector-react";
import { $memberId } from "store/authors";
import MembersPermissions from "./MembersPermissions";

type Props = {
  bootstrap: Bootstrap;
}

const Authors = ({ bootstrap }: Props) => {
  const memberId = useUnit($memberId)

  return (
    <div className="grid md:grid-cols-3 gap-4">
      {memberId === 0
        ? <AuthorsWrapper bootstrap={bootstrap} />
        : <MembersPermissions authorId={memberId} />
      }
    </div>
  )
}

export default Authors

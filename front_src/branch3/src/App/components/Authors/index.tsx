import { useEffect } from "react"
import Select from "reused/Select"
import type { Bootstrap } from "schema/input"
import { $authorsPayload, getAuthorsFx } from "store/authors"
import BranchAuthors from "./BranchAuthors"
import AuthorsChoice from "./AutrhorsChoice"
import { useUnit } from "effector-react"

type Props = {
  bootstrap: Bootstrap;
}

const Authors = ({ bootstrap }: Props) => {
  const authorsPayload = useUnit($authorsPayload)

  useEffect(() => {
    getAuthorsFx(authorsPayload)
  }, [authorsPayload])

  return (
    <div className="grid md:grid-cols-3 gap-4">
      <fieldset className="fieldset">
        <Select
          fieldName="masterId"
          label="Team leader"
          options={bootstrap.ownAuthors}
        />
        <BranchAuthors />
      </fieldset>
      <div className="md:col-span-2">
        <AuthorsChoice />
      </div>
    </div>
  )
}

export default Authors

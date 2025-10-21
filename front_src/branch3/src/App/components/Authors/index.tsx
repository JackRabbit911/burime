import { useEffect } from "react"
import Select from "reused/Select"
import type { Bootstrap } from "schema/input"
import { getAuthorsFx } from "store/authors"
import BranchAuthors from "./BranchAuthors"
import AuthorsChoice from "./AutrhorsChoice"

type Props = {
  bootstrap: Bootstrap;
}

const Authors = ({ bootstrap }: Props) => {
  useEffect(() => {
    getAuthorsFx()
  }, [])

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

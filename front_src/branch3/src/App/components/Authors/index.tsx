import Select from "reused/Select"
import type { Bootstrap } from "schema/input"
import AuthorsChoice from "./AutrhorsChoice"
import { useUnit } from "effector-react"
import { $authors } from "store/authors"
import Members from "./Members"

type Props = {
  bootstrap: Bootstrap;
}

const Authors = ({ bootstrap }: Props) => {
  const authors = useUnit($authors)

  return (
    <div className="grid md:grid-cols-3 gap-4">
      <fieldset className="fieldset">
        <Select
          fieldName="masterId"
          label="Team leader"
          options={bootstrap.ownAuthors}
        />
        <Members />
      </fieldset>
      <div className="md:col-span-2">
        <AuthorsChoice authors={authors} />
      </div>
    </div>
  )
}

export default Authors

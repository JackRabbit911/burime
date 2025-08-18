import { useUnit } from "effector-react"
import { $ownAuthors, masterIdSelected } from "../../store/authors"
import Select, { type Option } from "../../reused/Select"
import { $masterId } from "../../store/branch"
import AuthorsChoice from "./AuthorsChoice"
import BranchAuthors from "./BranchAuthors"

const Authors = () => {
  const ownAuthors = useUnit($ownAuthors)
  const masterId = useUnit($masterId)

  const options: Option[] = ownAuthors.map(
    ({ id, alias }) => ({
      value: id,
      name: alias,
    })
  )

  return (
    <div className="grid md:grid-cols-3 gap-4">
      <fieldset className="fieldset">
        <Select
          label="Team leader"
          value={masterId}
          options={options}
          onChange={masterIdSelected}
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

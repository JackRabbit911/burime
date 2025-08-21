import { useUnit } from "effector-react"
import { $authorsPayload, $ownAuthorsOptions, getAuthorsFx, masterIdSelected } from "../../store/authors"
import Select from "../../reused/Select"
import { $masterId } from "../../store/branch"
import AuthorsChoice from "./AuthorsChoice"
import BranchAuthors from "./BranchAuthors"
import { useEffect } from "react"

const Authors = () => {
  const authorsPayload = useUnit($authorsPayload)
  const masterId = useUnit($masterId)
  const options = useUnit($ownAuthorsOptions)

  useEffect(() => {
    getAuthorsFx(authorsPayload)
  }, [])

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

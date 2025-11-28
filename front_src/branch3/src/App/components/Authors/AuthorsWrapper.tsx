import Select from "reused/Select"
import type { Bootstrap } from "schema/input"
import AuthorsChoice from "./AutrhorsChoice"
import Members from "./Members"

type Props = {
  bootstrap: Bootstrap;
}

const AuthorsWrapper = ({ bootstrap }: Props) => {

  return (
    <>
      <fieldset className="fieldset">
        <Select
          fieldName="masterId"
          label="Team leader"
          options={bootstrap.ownAuthors}
        />
        <Members
          ownAuthors={bootstrap.ownAuthors}
        />
      </fieldset>
      <div className="md:col-span-2">
        <AuthorsChoice
          filters={bootstrap.authorsFilters}
        />
      </div>
    </>
  )
}

export default AuthorsWrapper

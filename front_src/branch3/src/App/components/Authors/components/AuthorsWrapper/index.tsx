import type { Bootstrap } from "schema/input"
import Members from "./components/Members";
import AuthorsChoice from "./components/AuthorsChoice";
import Select from "./components/Select";

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

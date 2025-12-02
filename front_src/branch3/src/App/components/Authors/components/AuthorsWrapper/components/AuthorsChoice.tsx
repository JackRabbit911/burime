import { useFormContext } from "react-hook-form"
import type { Author, Member } from "schema/authors"
import type { AuthorsFilters } from "schema/input"
import { useUnit } from "effector-react"
import { $authors } from "store/authors"
import { addNewMember, isInvited } from "../../../utils"
import AuthorSearch from "./AuthorSearch"
import Pagination from "./Pagination"

type Props = {
  filters: AuthorsFilters;
}

const AuthorsChoice = ({ filters }: Props) => {
  const authors = useUnit($authors)
  const { getValues, setValue } = useFormContext()
  const members = getValues('members')

  const inviteHandle = (members: Member[], author: Author) => () => {
    const branchMembers = addNewMember(members, author)
    setValue('members', branchMembers, { shouldValidate: true, shouldDirty: true })
  }

  return (
    <>
      <AuthorSearch filters={filters} />
      <div className="flex flex-wrap gap-2 mt-1">
        {authors?.list.map((author, key) => (
          <button
            className="btn btn-soft btn-outline btn-sm"
            disabled={isInvited(members, author.id)}
            onClick={inviteHandle(members, author)}
            key={key}
          >
            {author.alias}
          </button>
        ))}
      </div>
      <Pagination />
    </>
  )
}

export default AuthorsChoice

import { useFormContext } from "react-hook-form"
import { useEffect } from "react"
import { getAuthorsFx } from "store/authors"
import { addNewMember, isInvited } from "./utils"
import AuthorSearch from "./AuthorSearch"
import Pagination from "./Pagination"
import type { Author, Authors, Member } from "schema/authors"
import type { AuthorsFilters } from "schema/input"

type Props = {
  authors: Authors | null;
  filters: AuthorsFilters;
}

const AuthorsChoice = ({ authors, filters }: Props) => {
  const { getValues, setValue } = useFormContext()

  const members = getValues('members')
  const authorsPayload = getValues('authorsPayload')

  const inviteHandle = (members: Member[], author: Author) => () => {
    const branchMembers = addNewMember(members, author)
    setValue('members', branchMembers, { shouldValidate: true, shouldDirty: true })
  }

  useEffect(() => {
    getAuthorsFx(authorsPayload)
  }, [])

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

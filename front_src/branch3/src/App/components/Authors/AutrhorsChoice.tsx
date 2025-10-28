import { useUnit } from "effector-react"
import { useFormContext } from "react-hook-form"
import { $authors, getAuthorsFx } from "store/authors"
import type { Author, Member } from "schema/authors"
import { addNewMember, isInvited } from "./utils"
import AuthorSearch from "./AuthorSearch"
import { useEffect } from "react"
import Pagination from "./Pagination"

const AuthorsChoice = () => {
  const { getValues, setValue } = useFormContext()

  const authors = useUnit($authors)
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
      <AuthorSearch />
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

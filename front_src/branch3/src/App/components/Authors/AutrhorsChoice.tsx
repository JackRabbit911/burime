import { useUnit } from "effector-react"
import { useFormContext } from "react-hook-form"
import { $authors, getAuthorsFx } from "store/authors"
import type { Author, BranchAuthor } from "schema/authors"
import { addNewMember, isInvited } from "./utils"
import AuthorSearch from "./AuthorSearch"
import { useEffect } from "react"

const AuthorsChoice = () => {
  const { getValues, setValue } = useFormContext()

  const authors = useUnit($authors)
  const members = getValues('authors')
  const authorsPayload = getValues('authorsPayload')

  const inviteHandle = (members: BranchAuthor[], author: Author) => () => {
    const branchAuthors = addNewMember(members, author)
    setValue('authors', branchAuthors, { shouldValidate: true, shouldDirty: true })
  }

  useEffect(() => {
    getAuthorsFx(authorsPayload)
  }, [])

  return (
    <>
      <AuthorSearch />
      <div className="flex flex-wrap gap-2 mt-1">
        {authors?.authors.map((author, key) => (
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
      {/* <Pagination /> */}
    </>
  )
}

export default AuthorsChoice

import { useUnit } from "effector-react"
import { useFormContext } from "react-hook-form"
import { $authors } from "store/authors"
import type { Author, BranchAuthor } from "schema/authors"
import { addNewMember, isInvited } from "./utils"

const AuthorsChoice = () => {
  const { getValues, setValue } = useFormContext()

  const authors = useUnit($authors)
  const members = getValues('authors')

  const inviteHandle = (members: BranchAuthor[], author: Author) => () => {
    const branchAuthors = addNewMember(members, author)
    setValue('authors', branchAuthors, { shouldValidate: true, shouldDirty: true })
  }

  return (
    <>
      {/* <AuthorsFilter />
      <AuthorSearch /> */}
      <div className="flex flex-wrap gap-2">
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

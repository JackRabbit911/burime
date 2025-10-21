import { useUnit } from "effector-react"
import { useFormContext } from "react-hook-form"
import { $authors } from "store/authors"
import type { Author, BranchAuthor } from "schema/authors"

const isInvited = (
    array: BranchAuthor[],
    id: number,
): boolean => (
    Boolean(
        array.find((elem: BranchAuthor) => elem.id === id)
    )
)

const AuthorsChoice = () => {
    const { getValues, setValue } = useFormContext()

  const authors = useUnit($authors)
  const invited = getValues('authors')
  const inviteHandle = (invited: BranchAuthor[], author: Author) => () => {
    const invitedAuthor = {
        id: author.id,
        role: 50,
        status: 70,
        alias: author.alias,
    }

    const branchAuthors = [...invited, invitedAuthor]

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
            disabled={isInvited(invited, author.id)}
            onClick={inviteHandle(invited, author)}
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

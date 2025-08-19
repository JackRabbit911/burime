import { useUnit } from "effector-react";
import { $authors, authorInvited } from "../../store/authors";
import { isAuthorInBranch } from "../../store/authors/utils";
import { $branchAuthors } from "../../store/branch";
import type { Author } from "../../store/authors/types";
import AuthorsFilter from "./AuthorsFilter";

const AuthorsChoice = () => {
  const branchAuthors = useUnit($branchAuthors)
  const authors = useUnit($authors)
  const inviteHandle = (author: Author) => () => { authorInvited(author) }

  return (
    <>
      <AuthorsFilter />
      <div className="flex flex-wrap gap-2">
        {authors.map((author, key) => (
          <button
            className="btn btn-soft btn-outline btn-sm"
            onClick={inviteHandle(author)}
            disabled={isAuthorInBranch(branchAuthors, author.id)}
            key={key}
          >
            {author.alias}
          </button>
        ))}
      </div>
    </>
  )
}

export default AuthorsChoice

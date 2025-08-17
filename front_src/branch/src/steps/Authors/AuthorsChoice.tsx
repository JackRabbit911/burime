import { useUnit } from "effector-react";
import { $authors, authorInvited } from "../../store/authors";
import { isAuthorInBranch } from "../../store/authors/utils";
import { $branchAuthors } from "../../store/branch";

const AuthorsChoice = () => {
  const branchAuthors = useUnit($branchAuthors)
  const authors = useUnit($authors)
  const inviteHandle = (id: number) => () => {authorInvited(id)}

  return (
    <>
      <div className="flex flex-wrap gap-2">
        {authors.map((item, key) => (
          <button
            className="btn btn-soft btn-outline btn-sm"
            onClick={inviteHandle(item.id)}
            disabled={isAuthorInBranch(branchAuthors, item.id)}
            key={key}
          >
            {item.alias}
          </button>
        ))}
      </div>
    </>
  )
}

export default AuthorsChoice

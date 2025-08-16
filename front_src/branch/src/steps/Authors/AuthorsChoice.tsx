import { useUnit } from "effector-react";
import { authorInvited } from "../../store/authors";
import type { Author } from "../../store/authors/types"
import { isAuthorInBranch } from "../../store/authors/utils";
import { $branchAuthors } from "../../store/branch";

type Props = {
  authors: Author[];
}

const AuthorsChoice = ({ authors }: Props) => {
  const branchAuthors = useUnit($branchAuthors)
  const inviteHandle = (id: number) => () => {authorInvited(id)}

  return (
    <>
      <div className="flex flex-wrap gap-2">
        {authors.map((item) => (
          <button
            className="btn btn-soft btn-outline btn-sm"
            onClick={inviteHandle(item.id)}
            disabled={isAuthorInBranch(branchAuthors, item.id)}
          >
            {item.alias}
          </button>
        ))}
      </div>
    </>
  )
}

export default AuthorsChoice

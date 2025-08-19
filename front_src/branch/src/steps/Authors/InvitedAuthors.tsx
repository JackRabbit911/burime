import type { BranchAuthor } from "../../store/authors/types"
import { authorRemoved, authorRoleToggled } from "../../store/authors";

type Props = {
  author: BranchAuthor;
}

const InvitedAuthors = ({author}: Props) => {
  const deleteBranchAuthor = (author: BranchAuthor) => () => { authorRemoved(author) }
  const changeAuthorRole = (author: BranchAuthor) => () => { authorRoleToggled(author) }

  return (
    <div className="flex flex-row justify-between gap-2">
      <label className="fieldset-label flex justify-between w-full">
        <legend className="fieldset-legend me-0.5 pb-1 pt-0">{author.alias}</legend>
        <input
          type="checkbox"
          className="checkbox"
          checked={author.role === 100}
          onChange={changeAuthorRole(author)}
        />
      </label>
      <button
        className="btn btn-outline btn-square btn-xs rounded-lg"
        onClick={deleteBranchAuthor(author)}
      >
        <span className=" text-red-600">X</span>
      </button>
    </div>
  )
}

export default InvitedAuthors

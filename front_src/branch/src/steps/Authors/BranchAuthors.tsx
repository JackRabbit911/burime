import { useList } from "effector-react";
import { $branchAuthors } from "../../store/branch";
import type { BranchAuthor } from "../../store/authors/types";
import { authorRemoved, authorRoleToggled } from "../../store/authors";

const BranchAuthors = () => {
  const deleteBranchAuthor = (author: BranchAuthor) => () => { authorRemoved(author) }
  const changeAuthorRole = (author: BranchAuthor) => () => { authorRoleToggled(author) }

  const authors = useList($branchAuthors, (author) => (
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
  ))

  return (
    <>
      <legend className="fieldset-legend">
        Invited
      </legend>
      <div className="flex flex-col gap-2">
        {authors}
      </div>
    </>
  )
}

export default BranchAuthors

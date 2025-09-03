import { useList } from "effector-react";
import { $branchAuthors } from "../../store/branch";
import InvitedAuthors from "./InvitedAuthors";

const BranchAuthors = () => {
  const authors = useList($branchAuthors, (author) => (
    <InvitedAuthors author={author} />
  ))

  return (
    <>
      <div>
        <legend className="fieldset-legend flex justify-between">
          <span>Alias</span>
          <span className="me-4">Moderator</span>
        </legend>
      </div>
      <div className="flex flex-col gap-2">
        {authors}
      </div>
    </>
  )
}

export default BranchAuthors

import { useFormContext } from "react-hook-form";
import type { Member } from "schema/authors"
import InvitedAuthors from "./InvitedAuthors";

const Members = () => {
  const { getValues } = useFormContext()
  const authors = getValues('members')

  return (
    <>
      <div>
        <legend className="fieldset-legend flex justify-between">
          <span>Alias</span>
          <span className="me-4">Moderator</span>
        </legend>
      </div>
      <div className="flex flex-col gap-2">
        {authors.map(
          (author: Member) => (
            <InvitedAuthors fieldName="moderator" author={author} key={author.id} />
          )
        )}
      </div>
    </>
  )
}

export default Members

import { useFormContext } from "react-hook-form";
import type { BranchAuthor } from "schema/authors";

type Props = {
  fieldName: string;
  author: BranchAuthor;
}

const InvitedAuthors = ({ fieldName, author }: Props) => {
  const { register, getValues, setValue, watch } = useFormContext()

  const checked = getValues('moderator').includes(author.id)

  const deleteMember = (author: BranchAuthor) => () => {
    const members = watch('members').filter((item: BranchAuthor) => item.id !== author.id)
    setValue('members', members)
  }

  return (
    <div className="flex flex-row justify-between gap-2">
      <label className="fieldset-label flex justify-between w-full">
        <legend className="fieldset-legend me-0.5 pb-1 pt-0">{author.alias}</legend>
        <input
          type="checkbox"
          className="checkbox"
          value={author.id}
          defaultChecked={checked}
          {...register(fieldName)}
        />
      </label>
      <button
        className="btn btn-outline btn-square btn-xs rounded-lg"
        onClick={deleteMember(author)}
      >
        <span className=" text-red-600">X</span>
      </button>
    </div>
  )
}

export default InvitedAuthors

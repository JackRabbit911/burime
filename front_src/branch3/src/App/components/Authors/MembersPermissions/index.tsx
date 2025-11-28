import { useFormContext } from "react-hook-form";
import type { Member } from "schema/authors";
import { memberIdResetted, memberIdSetted } from "store/authors";
import { getCurrentMember } from "./utils";
import { isPermission, permissions } from "./permissions";
import PermissionCheckBox from "./PermissionCheckBox";

type Props = {
  id: number;
}

const MembersPermissions = ({ id }: Props) => {
  const { setValue, getValues } = useFormContext()

  const authors = getValues('members')
  const currentAuthor = getCurrentMember(authors, id)
  const checked = (value: number): boolean => isPermission(currentAuthor?.role || 0, value)

  const handleCheck = (val: number, id: number, isAdd: boolean) => {
    const newAuthors = authors.map((value: Member) => {
      if (value.id === id) {
        value.role = isAdd ? value.role | val : value.role &= ~ val
      }

      return value
    })

    setValue('members', newAuthors)
  }

  return (
    <>
      <div className="md:col-span-3">
        <h2 className="text-lg">
          {currentAuthor?.alias || id}
        </h2>
      </div>
      <fieldset className="fieldset">
        <h3>Participants</h3>
        {authors.map(
          (author: Member) => (
            <button
              key={author.id}
              className="btn btn-soft btn-sm"
              disabled={author.id === id}
              onClick={() => {
                memberIdSetted(author.id)
              }}
            >
              {author.alias}
            </button>
          )
        )}
      </fieldset>
      <fieldset className="fieldset">
        <h3>Permissions</h3>
        {Object.entries(permissions).reverse().map(([label, value]) => (
          <PermissionCheckBox
            handler={handleCheck}
            memberId={id}
            label={label}
            key={`${label}.${currentAuthor?.id}`}
            value={value}
            checked={checked(value)}
          />
        ))}
        <div>{currentAuthor?.role}</div>
        <button className="btn"
          onClick={() => memberIdResetted()}
        >
          Close
        </button>
      </fieldset>
      <fieldset className="fieldset">
        <h3>Status</h3>
        <button className="btn btn-soft btn-sm"
        >
          Make moderator
        </button>
      </fieldset>
    </>
  )
}

export default MembersPermissions

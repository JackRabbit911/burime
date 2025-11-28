import { useFormContext } from "react-hook-form";
import type { Member } from "schema/authors";
import { memberIdResetted } from "store/authors";
import { getCurrentMember } from "../utils";
import PermissionsList from "./PermissionsList";
import { moderatorPerm } from "../permissions";
// import { t } from "i18n/utils";
import Participants from "./Participants";

type Props = {
  authorId: number;
}

const MembersPermissions = ({ authorId }: Props) => {
  const { setValue, getValues } = useFormContext()

  const members = getValues('members')
  const currentAuthor = getCurrentMember(members, authorId)

  const handleSetPermission = (permission: number) => () => {
    const newMembers = members.map((value: Member) => {
      if (value.id === authorId) {
        value.role = permission
      }

      return value
    })

    setValue('members', newMembers)
  }

  return (
    <>
      <div className="md:col-span-3">
        <h2 className="text-lg">
          {currentAuthor?.alias || authorId}
        </h2>
      </div>
      <fieldset className="fieldset">
        <Participants
          members={members}
          authorId={authorId}
        />
      </fieldset>
      <fieldset className="fieldset">
        <PermissionsList
          member={currentAuthor}
        />
        <button className="btn"
          onClick={() => memberIdResetted()}
        >
          Close
        </button>
      </fieldset>
      <fieldset className="fieldset">
        <h3>Status</h3>
        <button
          className="btn btn-soft btn-sm"
          onClick={handleSetPermission(moderatorPerm)}
        >
          Make moderator
        </button>
      </fieldset>
    </>
  )
}

export default MembersPermissions

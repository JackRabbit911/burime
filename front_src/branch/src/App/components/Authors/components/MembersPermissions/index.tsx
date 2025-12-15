import { useFormContext } from "react-hook-form";
import { getCurrentMember } from "../../utils";
import PermissionsList from "./components/PermissionsList";
import Participants from "./components/Participants";
import Status from "./components/Status";
import { memberIdResetted } from "store/authors";
import { t } from "i18n/utils";
import { useUnit } from "effector-react";

type Props = {
  authorId: number;
}

const MembersPermissions = ({ authorId }: Props) => {
  const onClose = useUnit(memberIdResetted)
  const { getValues } = useFormContext()
  const members = getValues('members')
  const currentAuthor = getCurrentMember(members, authorId)

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
      <div className="md:col-span-2 grid grid-cols-2 gap-4">
        <fieldset className="fieldset">
          <PermissionsList
            member={currentAuthor}
          />
        </fieldset>
        <fieldset className="fieldset">
          <Status
            member={currentAuthor}
          />
        </fieldset>
        <button className="md:col-span-2 btn btn-sm"
          onClick={onClose}
        >
          {t('Close')}
        </button>
      </div>
    </>
  )
}

export default MembersPermissions

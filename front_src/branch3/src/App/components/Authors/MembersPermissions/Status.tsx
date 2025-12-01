import { useFormContext } from "react-hook-form";
import type { Member } from "schema/authors";
import { getStatus, memberStatus, moderatorPerm } from "../permissions";
import { t } from "i18n/utils";
import { isAccept, isBan, isModerator } from "../utils";
import { host } from "services/ajax";

type Props = {
  member: Member | null;
}

const Status = ({ member }: Props) => {
  const { getValues, setValue } = useFormContext()

  const members = getValues('members')
  const status = getStatus(member?.status || 0)

  const addPermission = (permission: number) => () => {
    const newMembers = members.map((value: Member) => {
      if (value.id === member?.id) {
        value.role = value.role | permission
      }

      return value
    })

    setValue('members', newMembers)
  }

  const setStatus = (status: number) => () => {
    const newMembers = members.map((value: Member) => {
      if (value.id === member?.id) {
        value.status = status
      }

      return value
    })

    setValue('members', newMembers)
  }

  return (
    <>
      <h3>{t('Status')} {t(status)}</h3>
      <button
        className="btn btn-soft btn-sm"
        onClick={addPermission(moderatorPerm)}
        disabled={isModerator(member)}
      >
        {t('Make moderator')}
      </button>
      <button
        className="btn btn-soft btn-sm"
        onClick={setStatus(memberStatus.invited)}
        disabled={!isAccept(member)}
      >
        {t('Accept to project')}
      </button>
      <button
        className="btn btn-soft btn-sm"
        onClick={setStatus(memberStatus.denied)}
        disabled={member?.status!==memberStatus.candidate}
      >
        {t('Deny')}
      </button>
      <button
        className="btn btn-soft btn-error btn-sm"
        disabled={!isBan(member)}
      >
        {t('Ban')}
      </button>
      <button
        className="btn btn-soft btn-error btn-sm"
        disabled={(member?.status || 0) >= memberStatus.member}
      >
        {t('Delete')}
      </button>
      <button
        className="btn btn-soft btn-sm"
        onClick={() => {window.open(`${host}/author/${member?.id}`, '_blank')}}
      >
        {t('Show profile')}
      </button>
    </>
  )
}

export default Status

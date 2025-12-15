import { useFormContext } from "react-hook-form";
import type { Member } from "schema/authors";
import { getStatusString } from "../../../permissions";
import { t } from "i18n/utils";
import { host } from "services/ajax";
import { useUnit } from "effector-react";
import { $statusObj, $permissions } from "store/bootstrap";
import { buttonEnabled } from "../utils";
import { memberIdResetted } from "store/authors";

type Props = {
  member: Member | null;
}

const Status = ({ member }: Props) => {
  const permissions = useUnit($permissions)
  const statusObj = useUnit($statusObj)
  const { getValues, setValue } = useFormContext()

  const enable =  new buttonEnabled(permissions, statusObj, member)

  const members = getValues('members')
  const status = getStatusString(statusObj, member?.status || 0)

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

  const deleteMember = (author: Member | null) => () => {
    if (author) {
      setValue('members', members.filter((item: Member) => item.id !== author.id))
      memberIdResetted()
    }
  }

  return (
    <>
      <h3>{t('Status')} {t(status)} {member?.status}</h3>
      <button
        className="btn btn-soft btn-sm"
        onClick={addPermission(permissions.MANAGE | permissions.MODERATE)}
        disabled={!enable.moderator()}
      >
        {t('Make moderator')}
      </button>
      <button
        className="btn btn-soft btn-sm"
        onClick={setStatus(statusObj.invited)}
        disabled={!enable.accept()}
      >
        {t('Accept to project')} {statusObj.invited} qq
      </button>
      <button
        className="btn btn-soft btn-sm"
        onClick={setStatus(statusObj.denied)}
        disabled={!enable.deny()}
      >
        {t('Deny')}
      </button>
      <button
        className="btn btn-soft btn-error btn-sm"
        disabled={!enable.ban()}
      >
        {t('Ban')}
      </button>
      <button
        className="btn btn-soft btn-error btn-sm"
        onClick={deleteMember(member)}
        disabled={!enable.delete()}
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

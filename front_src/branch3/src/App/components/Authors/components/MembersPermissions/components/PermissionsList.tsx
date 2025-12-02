import { useFormContext } from "react-hook-form";
import { isPermission } from "../../../permissions";
import PermissionCheckBox from "./PermissionCheckBox";
import type { Member } from "schema/authors";
import { t } from "i18n/utils";
import { useUnit } from "effector-react";
import { $permissions } from "store/bootstrap";

type Props = {
  member: Member | null;
}

const PermissionsList = ({ member }: Props) => {
  const permissions = useUnit($permissions)
  const { setValue, getValues } = useFormContext()

  const members = getValues('members')
  const checked = (value: number): boolean => isPermission(member?.role || 0, value)

  const handleCheck = (val: number, id: number, isAdd: boolean) => {
    const newAuthors = members.map((value: Member) => {
      if (value.id === id) {
        value.role = isAdd ? value.role | val : value.role &= ~val
      }

      return value
    })

    setValue('members', newAuthors)
  }

  return (
    <>
      <h3>
        {t('Permissions')}
      </h3>
      {Object.entries(permissions).reverse().map(([label, value]) => (
        <PermissionCheckBox
          handler={handleCheck}
          member={member}
          label={label}
          key={`${label}.${member?.id}`}
          value={value}
          checked={checked(value)}
        />
      ))}
    </>
  )
}

export default PermissionsList

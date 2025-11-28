import type { ChangeEvent } from "react";
import type { Member } from "schema/authors";
import { t } from "i18n/utils";

type Props = {
  handler: (val: number, id: number, isAdd: boolean) => void;
  member: Member | null;
  label: string;
  value: number;
  checked: boolean;
}

const PermissionCheckBox = ({ handler, member, label, value, checked }: Props) => {
  const fieldName = 'perms'
      const handleChange = (event: ChangeEvent<HTMLInputElement>) => {
        handler(value, member?.id || 0, event.target.checked)
      };

  return (
    <label
      className="fieldset-label flex justify-between"
    >
      {t(label)}
      <input
        name={`${fieldName}.${member?.id}`}
        type="checkbox"
        className="checkbox checkbox-sm"
        value={value}
        checked={checked}
        onChange={handleChange}
      />
    </label>
  )
}

export default PermissionCheckBox

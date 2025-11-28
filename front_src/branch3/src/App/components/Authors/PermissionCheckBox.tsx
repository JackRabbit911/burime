import { useState, type ChangeEvent } from "react";

type Props = {
  handler: (val: number, id: number, isAdd: boolean) => void;
  memberId: number | undefined;
  label: string;
  value: number;
  checked: boolean;
}

const PermissionCheckBox = ({ handler, memberId, label, value, checked }: Props) => {
  const fieldName = 'perms'
  const [isChecked, setIsChecked] = useState(checked);

      const handleChange = (event: ChangeEvent<HTMLInputElement>) => {
        setIsChecked(event.target.checked)
        handler(value, memberId || 0, event.target.checked)
      };

  return (
    <label
      className="fieldset-label flex justify-between"
    >
      {label}
      <input
        name={`${fieldName}.${memberId}`}
        type="checkbox"
        className="checkbox checkbox-sm"
        value={value}
        checked={isChecked}
        onChange={handleChange}
      />
    </label>
  )
}

export default PermissionCheckBox

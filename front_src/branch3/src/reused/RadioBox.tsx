import { useFormContext } from "react-hook-form";

type Props = {
  label: string;
  value: number;
  fieldName: string;
}

const RadioBox = ({ label, value, fieldName }: Props) => {
  const { register, getValues } = useFormContext()

  const checked = getValues(fieldName) === value

  return (
    <label className="fieldset-label cursor-pointer flex justify-between mb-4">
      <legend className="fieldset-legend me-2 pb-1 pt-0">
        {label}
      </legend>
      <input
        type="radio"
        className="radio"
        {...register(fieldName)}
        defaultValue={value}
        defaultChecked={checked}
      />
    </label>
  )
}

export default RadioBox

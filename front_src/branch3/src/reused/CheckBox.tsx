import { useFormContext } from "react-hook-form";

type Props = {
  fieldName: string;
  label: string;
}

const CheckBox = ({fieldName, label }: Props) => {
  const { register, getValues } = useFormContext()
  const checked = Boolean(getValues(fieldName))

  return (
    <label className="fieldset-label flex justify-between">
      <legend className="fieldset-legend me-0.5 pb-1 pt-0">{label}</legend>
      <input
        type="checkbox"
        className="checkbox"
        defaultChecked={checked}
        {...register(fieldName)}
      />
    </label>
  )
}

export default CheckBox

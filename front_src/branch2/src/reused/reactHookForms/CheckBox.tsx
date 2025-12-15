import { useFormContext } from "react-hook-form";

type Props = {
  label?: string;
  fieldName: string;
}

const CheckBox = ({ fieldName, label = '' }: Props) => {
  const { register } = useFormContext()

  return (
    <label className="fieldset-label flex justify-between">
      <legend className="fieldset-legend me-0.5 pb-1 pt-0">{label}</legend>
      <input
        type="checkbox"
        className="checkbox"
        {...register(fieldName)}
      />
    </label>
  )
}

export default CheckBox

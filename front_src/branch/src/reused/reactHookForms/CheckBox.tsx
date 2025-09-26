import { useFormContext } from "react-hook-form";

type Props = {
  fieldName: string;
  label?: string;
}

const CheckBox = ({ fieldName, label='' }: Props) => {
  const methods = useFormContext()
  return (
    <label className="fieldset-label flex justify-between">
      <legend className="fieldset-legend me-0.5 pb-1 pt-0">{label}</legend>
      <input
        {...methods.register(fieldName)}
        type="checkbox"
        className="checkbox"
      />
    </label>
  )
}

export default CheckBox

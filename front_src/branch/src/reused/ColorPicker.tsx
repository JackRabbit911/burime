import { useFormContext } from "react-hook-form";

type Props = {
  fieldName: string;
  label: string;
}

const ColorPicker = ({ fieldName, label }: Props) => {
  const { register } = useFormContext();

  return (
    <div>
      <label className="fieldset-label">
        <legend className="fieldset-legend">
          {label}
        </legend>
      </label>
      <input type="color"
        className="input input-bordered input-md w-14 p-1"
        {...register(fieldName)}
      />
    </ div>
  )
}

export default ColorPicker

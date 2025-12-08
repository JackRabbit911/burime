import { useFormContext } from "react-hook-form";

type Props = {
  label?: string;
  value?: number;
  checked?: number[];
}

const GenreCheckBox = ({ label = '', value = 0, checked= []}: Props) => {
  const { register } = useFormContext()

  return (
    <label className="fieldset-label flex justify-between">
      <input
        type="checkbox"
        className="checkbox checkbox-sm"
        value={value}
        defaultChecked={checked.includes(value)}
        {...register('branch_genres')}
      />
      {label}
    </label>
  )
}

export default GenreCheckBox

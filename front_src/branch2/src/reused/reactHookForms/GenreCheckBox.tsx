import { useFormContext, Controller } from "react-hook-form";

type Props = {
  label?: string;
  fieldName: string;
}

const GenreCheckBox = ({ fieldName, label = '' }: Props) => {
  const { control, trigger, setValue } = useFormContext()

  return (
    <label className="fieldset-label flex justify-between">
      <legend className="fieldset-legend me-0.5 pb-1 pt-0">{label}</legend>
      <Controller
        control={control}
        name={fieldName}
        render={({ field: { value } }) => (
          <input
            type="checkbox"
            checked={value}
            className="checkbox"
            onChange={(event) => {
              setValue(fieldName, event.target.checked);
              trigger('genres');
            }}
          />
        )}
      />
    </label>
  )
}

export default GenreCheckBox

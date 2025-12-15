import { useFormContext } from "react-hook-form";
import type { OwnAuthors } from "schema/authors";

type Props = {
  fieldName: string;
  label: string;
  options: OwnAuthors;
}

const Select = ({ fieldName, label, options }: Props) => {
  const { register } = useFormContext()

  return (
    <>
      <legend className="fieldset-legend flex justify-between w-full">
        {label}
      </legend>
      <select
        className="select"
        {...register(fieldName, { required: true })}
      >
        {options.map(
          ({ id, alias }, key) => (
            <option value={id} key={key}>
              {alias}
            </option>
          )
        )}
      </select>
    </>
  )
}

export default Select

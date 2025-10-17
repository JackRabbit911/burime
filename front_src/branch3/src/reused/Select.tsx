import { useFormContext } from "react-hook-form";
import type { OwnAuthors } from "schema/authors";

type Props = {
  fieldName: string;
  label: string;
  options: OwnAuthors;
  // alert?: string;
}

const Select = ({ fieldName, label, options }: Props) => {
  const { register } = useFormContext()

  return (
    <>
      <label className="fieldset-label flex justify-between">
        <legend className="fieldset-legend">
          {label}
        </legend>
        {/* {alert && <span className="label-text text-error">{alert}</span>} */}
      </label>
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

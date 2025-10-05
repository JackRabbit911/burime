import { ErrorMessage } from "@hookform/error-message";
import { useFormContext } from "react-hook-form";

const label = "Title"
const optional = "Up to 8 words"
const fieldName = "branchTitle"
const placeholder = "Название произведения"

const Title = () => {
  const { register, formState: { errors } } = useFormContext();

  const inputClassName =
    !errors?.[fieldName] ?
      "input w-full" :
      "input w-full input-error";

  const alert = !errors?.[fieldName] ? null : <ErrorMessage
    as="div"
    name={fieldName}
    errors={errors}
    className="fieldset-label text-error"
  />

  return (
    <fieldset className="fieldset">
      <legend className="fieldset-legend flex justify-between w-full">
        {label}
        {alert}
        <span className="label-text">{optional}</span>
      </legend>
      <input
        {...register(fieldName)}
        placeholder={placeholder}
        className={inputClassName}
      />
    </fieldset>
  )
}

export default Title

import { ErrorMessage } from "@hookform/error-message";
import { useFormContext } from "react-hook-form";
import { getObjectProp } from "utils";

type Props = {
  fieldName: string;
  label: string;
  placeholder: string;
  rows: number;
  optional?: string;
  disabled?: boolean;
}

const Textarea = ({ fieldName, label, placeholder, rows, optional = '', disabled = false }: Props) => {
  const { register, formState: { errors } } = useFormContext();

  const err = getObjectProp(errors, fieldName)

  const alert = !err ? null : <ErrorMessage
      as="div"
      name={fieldName}
      errors={errors}
      className="fieldset-label text-error"
    />

    const textareaClassName =
      !err ? "textarea w-full" : "textarea w-full textarea-error";

  return (
    <div>
      <label className="fieldset-label flex justify-between">
        <legend className="fieldset-legend">{label}</legend>
        {alert}
        <span className="label-text">{optional}</span>
      </label>
      <textarea
        className={textareaClassName}
        placeholder={placeholder}
        rows={rows}
        disabled={disabled}
        {...register(fieldName)}
      />
    </ div>
  )
}

export default Textarea

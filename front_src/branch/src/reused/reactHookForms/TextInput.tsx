import type { Rules } from "App/types";
import { Controller, useFormContext, type FieldError } from "react-hook-form";

type Props = {
  label: string;
  fieldName: string;
  placeholder?: string;
  optional?: string;
  alert?: string;
  type?: string;
  rules?: Rules;
}

const TextInput = ({
  label,
  fieldName,
  placeholder = '',
  optional = '',
  alert = '',
  type = 'text',
  rules = {},
}: Props) => {
  const methods = useFormContext()

  return (
    <Controller
      control={methods.control}
      name={fieldName}
      rules={rules}
      render={({ field, formState: { errors } }) => {
        console.log(fieldName, field, errors)

        return (
          <fieldset className="fieldset">
            <label className="fieldset-label flex justify-between">
              <legend className="fieldset-legend">{label}</legend>
              {alert && <span className="label-text text-error">{alert}</span>}
              <span className="label-text">{optional}</span>
            </label>
            <input
              {...field}
              type={type}
              className="input w-full"
              placeholder={placeholder}
            />
            <div className="fieldset-label text-error">
              {(errors[fieldName] as FieldError)?.message || ''}
            </div>
          </ fieldset>
        );
      }}
    />
  )
}

export default TextInput

import { useFormContext } from "react-hook-form";
import { ErrorMessage } from "@hookform/error-message";

type Props = {
  label: string;
  type?: string;
  alert?: string;
  fieldName: string;
  optional?: string;
  placeholder?: string;
}

const TextInput = ({
  label,
  fieldName,
  optional = '',
  type = 'text',
  placeholder = '',
}: Props) => {
  const { register, formState: { errors } } = useFormContext();

  const inputClassName =
    !errors?.[fieldName] ?
    "input w-full" :
    "input w-full input-error";

  return (
    <fieldset className="fieldset">
      <label className="fieldset-label flex justify-between">
        <legend className="fieldset-legend">{label}</legend>
        <span className="label-text">{optional}</span>
      </label>
      <input
        type={type}
        {...register(fieldName)}
        placeholder={placeholder}
        className={inputClassName}
      />
      {!errors?.[fieldName] ? null : (
        <ErrorMessage
          as="div"
          name="title"
          errors={errors}
          className="fieldset-label text-error"
        />
      )}
    </fieldset>
  );
};

export default TextInput;

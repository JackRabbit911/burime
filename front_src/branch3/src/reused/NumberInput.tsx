import { useFormContext } from "react-hook-form";

type Props = {
  fieldName: string;
  label: string;
  minMaxStep: number[];
}

const NumberInput = ({ fieldName, label, minMaxStep }: Props) => {
  const { register, formState: { errors } } = useFormContext();

  const inputClassName =
    !errors?.[fieldName] ?
      "input input-sm w-24" :
      "input input-sm w-24 input-error";

  // console.log(getValues(fieldName))

  return (
    <>
      <label className="fieldset-label">
        <legend className="fieldset-legend">
          {label}
        </legend>
      </label>
      <input
        className={inputClassName}
        type="number"
        min={minMaxStep[0]}
        max={minMaxStep[1]}
        step={minMaxStep[2]}
        {...register(fieldName)}
      />
    </>
  )
}

export default NumberInput

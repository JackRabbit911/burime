import { useFormContext } from "react-hook-form";

type Props = {
  fieldName: string;
  label: string;
  min: number;
  max: number;
  step: number;
}

function Range({ fieldName, label, min, max, step }: Props) {
  const { register } = useFormContext()

  return (
    <div className="mt-2">
      <label className="fieldset-label">
        <legend className="fieldset-legend">
          {label}
        </legend>
      </label>
      <input
        min={min}
        max={max}
        step={step}
        type="range"
        className="range range-primary range-sm w-full"
        {...register(fieldName)}
      />
    </div>
  );
}

export default Range

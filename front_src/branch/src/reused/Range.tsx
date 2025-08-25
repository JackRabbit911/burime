type Props = {
  label: string;
  min: number;
  max: number;
  step: number;
  value: number;
  onChange: (value: number) => void;
}

function Range({ min, max, step, label, value, onChange }: Props) {
    const onChangeHandle = (event: React.ChangeEvent<HTMLInputElement>) => {
    const value = parseInt(event.target.value, 10)

    if (!Number.isNaN(value)) {
      onChange(value)
    }
  }

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
        className="range range-sm range-primary w-full"
        value={value}
        onChange={onChangeHandle}
      />
    </div>
  );
}

export default Range

type Props = {
  label: string;
  value: number;
  minMaxStep: number[];
  onChange: (value: number) => void;
}

const NumberInput = ({ label, value, minMaxStep, onChange }: Props) => {
  const onChangeHandle = (event: React.ChangeEvent<HTMLInputElement>) => {
    const value = parseInt(event.target.value, 10)

    if (!Number.isNaN(value)) {
      onChange(value)
    }
  }

  return (
    <>
      <label className="fieldset-label">
        <legend className="fieldset-legend">
          {label}
        </legend>
      </label>
      <input
        className="input input-sm w-24"
        type="number"
        min={minMaxStep[0]}
        max={minMaxStep[1]}
        step={minMaxStep[2]}
        value={value}
        onChange={onChangeHandle}
      />
    </>
  )
}

export default NumberInput

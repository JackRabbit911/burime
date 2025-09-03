type Props = {
  label: string;
  value: string;
  onChange: (value: string) => void;
}

const ColorPicker = ({ label, value, onChange }: Props) => {
  const onChangeHandle = (event: React.ChangeEvent<HTMLInputElement>) => {
    onChange(event.target.value)
  }

  return (
    <div>
      <label className="fieldset-label">
        <legend className="fieldset-legend">
          {label}
        </legend>
      </label>
      <input type="color"
        className="input input-bordered input-md w-14 p-1"
        value={value}
        onChange={onChangeHandle}
      />
    </ div>
  )
}

export default ColorPicker

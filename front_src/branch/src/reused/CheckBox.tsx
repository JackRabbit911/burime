type Props = {
  label: string;
  value: number;
  checked: boolean;
  onChange: (value: number) => void;
}

const CheckBox = ({label, value, checked, onChange}: Props) => {
  return (
    <label className="fieldset-label flex justify-between">
      <legend className="fieldset-legend me-0.5 pb-1 pt-0">{label}</legend>
      <input
        type="checkbox"
        checked={checked}
        className="checkbox"
        onChange={() => onChange(value)}
      />
    </label>
  )
}

export default CheckBox

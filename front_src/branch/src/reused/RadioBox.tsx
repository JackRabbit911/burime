type Props = {
  label: string;
  value: number;
  checked: boolean;
  onChange: (value: number) => void;
}

const RadioBox = ({label, value, checked, onChange}: Props) => {
  return (
    <label className="fieldset-label cursor-pointer flex justify-between mb-4">
      <legend className="fieldset-legend me-2 pb-1 pt-0">
        {label}
      </legend>
      <input
        type="radio"
        className="radio"
        checked={checked}
        onChange={() => onChange(value)}
      />
    </label>
  )
}

export default RadioBox

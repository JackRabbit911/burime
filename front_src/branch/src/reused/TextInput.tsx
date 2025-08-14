type Props = {
  label: string;
  value: string;
  placeholder: string;
  optional: string;
  onChange: (value: string) => void;
}

const TextInput = ({ label, value, placeholder, optional, onChange }: Props) => {
  const onChangeHandle = (event: React.ChangeEvent<HTMLInputElement>) => {
    onChange(event.target.value)
  }

  return (
    <div>
      <label className="fieldset-label flex justify-between">
        <legend className="fieldset-legend">
          {label}
        </legend>
        <span className="label-text">{optional}</span>
      </label>
      <input type={'text'}
        placeholder={placeholder}
        className="input w-full"
        value={value}
        onChange={onChangeHandle}
      />
    </ div>
  )
}

export default TextInput

type Props = {
  label: string;
  value?: string;
  placeholder: string;
  rows: number;
  optional?: string;
  disabled?: boolean;
  onChange: (value: string) => void;
}

const Textarea = ({ label, value, placeholder, rows, optional = '', disabled = false, onChange }: Props) => {
  const onChangeHandle = (event: React.ChangeEvent<HTMLTextAreaElement>) => {
    onChange(event.target.value)
  }

  return (
    <div>
      <label className="fieldset-label flex justify-between">
        <legend className="fieldset-legend">{label}</legend>
        <span className="label-text">{optional}</span>
      </label>
      <textarea
        className="textarea w-full"
        placeholder={placeholder}
        value={value}
        rows={rows}
        disabled={disabled}
        onChange={onChangeHandle}
      />
    </ div>
  )
}

export default Textarea

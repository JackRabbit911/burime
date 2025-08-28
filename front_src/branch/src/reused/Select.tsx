export type Option = {
  value: string | number;
  name: string;
}

type Props = {
  label: string;
  value: number | string | null;
  options: Option[];
  alert: string;
  onChange: (value: string) => void;
}

const Select = ({ label, value, options, alert, onChange }: Props) => {
  const onChangeHandle = (event: React.ChangeEvent<HTMLSelectElement>) => {
    onChange(event.target.value)
  }

  return (
    <>
    <label className="fieldset-label flex justify-between">
      <legend className="fieldset-legend">
        {label}
      </legend>
      {alert && <span className="label-text text-error">{alert}</span>}
    </label>
      <select
        className="select"
        value={value || ''}
        onChange={onChangeHandle}
      >
        {!value && (
          <option value="" disabled>Choice Your author</option>
        )}
        {options.map(
          ({ value, name }, key) => (
            <option value={value} key={key}>
              {name}
            </option>
          )
        )}
      </select>
    </>
  )
}

export default Select

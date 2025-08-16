export type Option = {
  value: string | number;
  name: string;
}

type Props = {
  label: string;
  value: number | string | null;
  options: Option[];
  onChange: (value: string) => void;
}

const Select = ({ label, value, options, onChange }: Props) => {
  const onChangeHandle = (event: React.ChangeEvent<HTMLSelectElement>) => {
    onChange(event.target.value)
  }

  return (
    <>
      <legend className="fieldset-legend">
        {label}
      </legend>
      <select
        className="select"
        value={value || ''}
        aria-placeholder="Select please"
        onChange={onChangeHandle}
      >
        {value === null && (
          <option value=""></option>
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

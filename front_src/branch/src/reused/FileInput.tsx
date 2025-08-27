import { coverFileChanged } from "../store/common";

type Props = {
  label: string;
  optional: string;
  // onChange: (value: string) => void;
}

const FileInput = ({ label, optional }: Props) => {
  const onChangeHandle = (event: React.ChangeEvent<HTMLInputElement> | undefined) => {
    if (event?.target.files && event.target.files[0]) {
      coverFileChanged(event.target.files[0])
    }
  }

  return (
    <div>
      <label className="fieldset-label flex justify-between">
        <legend className="fieldset-legend">
          {label}
        </legend>
        <span className="label-text">{optional}</span>
      </label>
      <input type="file"
        className="file-input w-full"
        onChange={onChangeHandle}
      />
    </ div>
  )
}

export default FileInput

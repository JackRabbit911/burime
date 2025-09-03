import { bgFileCancelled, bgFileChanged, coverFileCancelled, coverFileChanged } from "../store/common";

type Props = {
  label: string;
  optional: string;
  event: string;
}

const FileInput = ({ label, optional, event = 'background' }: Props) => {

  const fileChangeEvent = event === 'background' ? bgFileChanged : coverFileChanged
  const fileCancelEvent = event === 'background' ? bgFileCancelled : coverFileCancelled

  const onChangeHandle = (event: React.ChangeEvent<HTMLInputElement> | undefined) => {
    if (event?.target.files && event.target.files[0]) {
      fileChangeEvent(event.target.files[0])
    }
  }

  return (
    <div className="basis-3/4">
      <label className="fieldset-label flex justify-between">
        <legend className="fieldset-legend">
          {label}
        </legend>
        <span className="label-text">{optional}</span>
      </label>
      <div className="join w-full">
        <input type="file"
          className="file-input w-full join-item"
          onChange={onChangeHandle}
        />
        <button
          className="btn basis-1/4 join-item border-base-100"
          onClick={() => fileCancelEvent()}
        >
          Cansel
        </button >

      </div>
    </ div>
  )
}

export default FileInput

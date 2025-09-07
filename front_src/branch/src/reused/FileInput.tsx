import { bgFileCancelled, bgFileChanged, coverFileCancelled, coverFileChanged } from "../store/common";

type Props = {
  label: string;
  optional: string;
  event: string;
  value?: string;
}

const FileInput = ({ label, optional, event = 'background', value }: Props) => {

  const fileChangeEvent = event === 'background' ? bgFileChanged : coverFileChanged
  const fileCancelEvent = event === 'background' ? bgFileCancelled : coverFileCancelled

  const onChangeHandle = (event: React.ChangeEvent<HTMLInputElement> | undefined) => {
    if (event?.target.files && event.target.files[0]) {
      fileChangeEvent(event.target.files[0])
    }
  }

  return (
    <div className="basis-3/4">
      <label className="fieldset-label flex flex-col w-full">
        <div className="flex justify-between w-full">
          <legend className="fieldset-legend">
            {label}
          </legend>
          <div className="label-text pt-2.5">{optional}</div>
        </div>
        <input type="file"
          style={{display: "none"}}
          onChange={onChangeHandle}
        />
        <div className="join w-full border border-zinc-600 rounded-sm">
          <div className="w-1/2 sm:w-1/3 bg-base-300 text-center flex flex-col justify-center">Выберите файл</div>
          <div className="w-1/2 sm:w-2/3 text-center  flex flex-col justify-center">{value || 'Файл не выбран'}</div>
          <button
            className="btn basis-1/4 join-item border-base-100"
            onClick={() => fileCancelEvent()}
          >
            Cansel
          </button >
        </div>
      </label>
    </ div>
  )
}

export default FileInput

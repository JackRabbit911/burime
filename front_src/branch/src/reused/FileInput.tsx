import { Controller, useFormContext } from "react-hook-form";

type Props = {
  fieldName: string;
  label: string;
  optional: string;
}

const FileInput = ({ fieldName, label, optional }: Props) => {
  const { control, watch, setValue, clearErrors, formState: { errors } } = useFormContext()

  const file = watch(fieldName)
  const fileName = file?.name || 'Файл не выбран'

  const reset = (fieldName: string) => {
    setValue(fieldName, null)
    clearErrors(fieldName)
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
        <Controller
          name={fieldName}
          control={control}
          render={({ field: { onChange, onBlur, name, ref } }) => (
            <input
              type="file"
              style={{display: "none"}}
              id={fieldName}
              name={name}
              onBlur={onBlur}
              onChange={(e) => {
                const file = e.target.files?.[0];
                onChange(file);
              }}
              ref={ref}
            />
          )}
        />
        <div className="join w-full border border-zinc-600 rounded-sm">
        {/* <div className="join w-full border border-red-500 rounded-sm"> */}
          <div className="w-1/2 sm:w-1/3 bg-base-300 text-center flex flex-col justify-center">Выберите файл</div>
          <div className="w-1/2 sm:w-2/3 text-center  flex flex-col justify-center">{fileName}</div>
          <button
            className="btn basis-1/4 join-item"
            onClick={() => reset(fieldName)}
          >
            Cansel
          </button >
        </div>
      </label>
      <div className="fieldset-label text-error mt-1">
        {errors[fieldName]?.message as string}
      </div>
    </ div>
  )
}

export default FileInput

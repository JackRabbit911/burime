import ColorPicker from "reused/ColorPicker";
import FileInput from "reused/FileInput";
import Range from "reused/Range";

const CoverControls = () => {
  return (
    <fieldset className="fieldset md:col-span-2">
      <legend className="fieldset-legend mb-3">
        Choose colors
      </legend>
      <div className="flex flex-row justify-around">
        <ColorPicker
          fieldName="branch.info.bg_color"
          label="Background"
        />
        <ColorPicker
          fieldName="branch.info.text_color"
          label="Text"
        />
      </div>
      <Range
        fieldName="branch.info.text_size"
        label="Font size"
        min={5}
        max={50}
        step={1}
      />
      <div className="divider mt-8 mb-4 text-lg text-current/75">or</div>
        <FileInput
          fieldName="cover"
          label="Cover image"
          optional="Up to 2Mb"
        />
        <FileInput
          fieldName="bgImg"
          label="Background image"
          optional="Up to 2Mb"
        />
    </fieldset>
  )
}

export default CoverControls

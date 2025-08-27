import ColorPicker from "../../reused/ColorPicker"
import FileInput from "../../reused/FileInput"
import Range from "../../reused/Range"
import type { Info } from "../../store/bootstrap/types"
import { bgColorChanged, textColorChanged, textSizeChanged } from "../../store/branch"

type Props = {
  info: Info
}

const CoverControls = ({ info }: Props) => {
  return (
    <fieldset className="fieldset md:col-span-2">
      <legend className="fieldset-legend mb-3">
        Choose colors
      </legend>
      <div className="flex flex-row justify-around">
        <ColorPicker
          label="Background"
          value={info.bg_color}
          onChange={bgColorChanged}
        />
        <ColorPicker
          label="Text"
          value={info.text_color}
          onChange={textColorChanged}
        />
      </div>
      <Range
        min={10}
        max={50}
        step={1}
        value={info.text_size}
        label="Font size"
        onChange={textSizeChanged}
      />
      <div className="divider my-8 text-lg text-current/75">or</div>

        <FileInput
          label="Cover image"
          optional="Up to 2Mb"
          event="cover"
        />
        <FileInput
          label="Background image"
          optional="Up to 2Mb"
          event="background"
        />

    </fieldset>
  )
}

export default CoverControls

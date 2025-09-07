import ColorPicker from "../../reused/ColorPicker"
import FileInput from "../../reused/FileInput"
import Range from "../../reused/Range"
import { bgColorChanged, textColorChanged, textSizeChanged } from "../../store/branch"
import type { Info } from "../../store/bootstrap/types"

type Props = {
  info: Info;
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
        min={5}
        max={50}
        step={1}
        value={info.text_size}
        label="Font size"
        onChange={textSizeChanged}
      />
      <div className="divider mt-8 mb-4 text-lg text-current/75">or</div>

        <FileInput
          label="Cover image"
          optional="Up to 2Mb"
          event="cover"
          value={info.cover}
        />
        <FileInput
          label="Background image"
          optional="Up to 2Mb"
          event="background"
          value={info.bg_img}
        />

    </fieldset>
  )
}

export default CoverControls

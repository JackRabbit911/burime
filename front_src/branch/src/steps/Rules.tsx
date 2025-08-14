import { useUnit } from "effector-react"
import RadioBox from "../reused/RadioBox"
import { $branch, ageLimitChanged, allowCommentToggled, decriptionChanged, moderationToggled, postSizeChanged, rulesChanged, rwModeToggled, signatureToggled, timeLimitChanged, titleChanged } from "../store/branch"
import CheckBox from "../reused/CheckBox"
import NumberInput from "../reused/NumberInput"
import TextInput from "../reused/TextInput"
import Textarea from "../reused/Textarea"

const Rules = () => {
  const branch = useUnit($branch);

  return (
    <fieldset className="fieldset">
      <div className="grid md:grid-cols-3 gap-4">
        <div>
          <legend className="fieldset-legend ms-0 mx-1 mb-2">
            Read-write mode
          </legend>
          <RadioBox label={'Open'} value={0} checked={branch?.role === 0} onChange={rwModeToggled} />
          <RadioBox label={'Open-Closed'} value={1} checked={branch?.role === 1} onChange={rwModeToggled} />
          <RadioBox label={'Commercial'} value={2} checked={branch?.role === 2} onChange={rwModeToggled} />
          <div className="divider my-1"></div>
          <div className="flex flex-col gap-3">
            <CheckBox
              label={'Pre-moderation'}
              value={branch?.info.moderation || 0}
              checked={branch?.info.moderation === 1}
              onChange={moderationToggled}
            />
            <CheckBox
              label={'Allow comments'}
              value={branch?.info.allow_comments || 1}
              checked={branch?.info.allow_comments === 1}
              onChange={allowCommentToggled}
            />
            <CheckBox
              label={'Author`s signature under the post'}
              value={branch?.info.signature || 0}
              checked={branch?.info.signature === 1}
              onChange={signatureToggled}
            />
            <div className="flex flex-row justify-between">
              <NumberInput
                label="Age Limit"
                value={branch?.age_limit || 0}
                minMaxStep={[0, 21, 3]}
                onChange={ageLimitChanged}
              />
            </div>
            <div className="flex flex-row justify-between">
              <NumberInput
                label="Post Size"
                value={branch?.info.post_size || 200}
                minMaxStep={[50, 2000, 50]}
                onChange={postSizeChanged}
              />
            </div>
            <div className="flex flex-row justify-between">
              <NumberInput
                label="Time limit"
                value={branch?.info.time_limit || 120}
                minMaxStep={[30, 1440, 30]}
                onChange={timeLimitChanged}
              />
            </div>
          </div>
        </div>

        <div className="md:col-span-2">
          <div className="flex flex-col gap-3">
            <TextInput
              label="Title"
              value={branch?.title || ''}
              placeholder="Введите название произведения"
              optional="Up to 8 words"
              onChange={titleChanged}
            />
            <Textarea
              label="Decription"
              value={branch?.info.description || ''}
              placeholder="Описание проекта"
              rows={6}
              optional="Up to 200 words"
              onChange={decriptionChanged}
            />
            <Textarea
              label="Extra rules"
              value={branch?.info.rules || ''}
              placeholder="Частные правила проекта"
              rows={6}
              optional="Up to 200 words"
              onChange={rulesChanged}
            />
          </div>
        </div>
      </div>
    </fieldset>
  )
}

export default Rules

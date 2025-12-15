import RadioBox from "reused/RadioBox";
import CheckBox from "reused/CheckBox";
import NumberInput from "reused/NumberInput";
import Textarea from "reused/Textarea";
import { t } from "i18n/utils";

const Rules = () => {
  return (
    <fieldset className="fieldset">
      <div className="grid md:grid-cols-3 gap-4">
        <div>
          <legend className="fieldset-legend ms-0 mx-1 mb-2">
            Read-write mode
          </legend>
          <RadioBox fieldName="branch.role" label={'Open'} value={0} />
          <RadioBox fieldName="branch.role" label={'Open-Closed'} value={1} />
          <RadioBox fieldName="branch.role" label={'Commercial'} value={2} />
          <div className="divider my-1"></div>
          <div className="flex flex-col gap-3">
            <CheckBox
              fieldName={'branch.info.moderation'}
              label={t('Pre-moderation')}
            />
            <CheckBox
              fieldName={'branch.info.allow_comments'}
              label={t('Allow comments')}
            />
            <CheckBox
              fieldName={'branch.info.signature'}
              label={t('Author`s signature under the post')}
            />
            <div className="flex flex-row justify-between">
              <NumberInput
                fieldName="branch.age_limit"
                label={t("Age Limit")}
                minMaxStep={[0, 21, 3]}
              />
            </div>
            <div className="flex flex-row justify-between">
              <NumberInput
                fieldName="branch.info.post_size"
                label={t("Post Size")}
                minMaxStep={[50, 2000, 50]}
              />
            </div>
            <div className="flex flex-row justify-between">
              <NumberInput
                fieldName="branch.info.time_limit"
                label={t("Time limit")}
                minMaxStep={[30, 1440, 30]}
              />
            </div>
          </div>
        </div>

        <div className="md:col-span-2">
          <div className="flex flex-col gap-3">
            <Textarea
              fieldName="branch.info.description"
              label={t("Description")}
              placeholder="Описание проекта"
              rows={7}
              optional={t('Up to % words', 200)}
            />
            <Textarea
              fieldName="branch.info.rules"
              label={t("Extra rules")}
              placeholder="Частные правила проекта"
              rows={7}
              optional={t('Up to % words', 200)}
            />
          </div>
        </div>
      </div>
    </fieldset>
  )
}

export default Rules

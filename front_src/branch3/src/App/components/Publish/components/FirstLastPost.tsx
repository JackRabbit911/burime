import { t } from "i18n/utils";
import { useFormContext } from "react-hook-form";
import Textarea from "reused/Textarea"

const FirstLastPost = () => {
  const { getValues } = useFormContext()
  const postSize = getValues('postSize')

  return (
    <fieldset className="fieldset md:col-span-2">
      <Textarea
        fieldName="posts.first.body"
        label="First post"
        placeholder="Напишите что-нибудь"
        rows={7}
        optional={t('Up to % words', postSize)}
      />
      <Textarea
        fieldName="posts.last.body"
        label="Last post"
        placeholder="Если хотите, напишите финальные строки вашего произведения"
        rows={7}
        optional={t('Up to % words', postSize)}
      />
    </fieldset>
  )
}

export default FirstLastPost

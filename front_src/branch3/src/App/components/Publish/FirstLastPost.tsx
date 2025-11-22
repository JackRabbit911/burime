import { useFormContext } from "react-hook-form";
import Textarea from "reused/Textarea"

const FirstLastPost = () => {
  const { getValues } = useFormContext()
  const postSize = getValues('postSize')

  return (
    <fieldset className="fieldset md:col-span-2">
      <Textarea
        fieldName="firstPost"
        label="First post"
        placeholder="Напишите что-нибудь"
        rows={7}
        optional={`Up to ${postSize} words`}
      />
      <Textarea
        fieldName="lastPost"
        label="Last post"
        placeholder="Если хотите, напишите финальные строки вашего произведения"
        rows={7}
        optional={`Up to ${postSize} words`}
      />
    </fieldset>
  )
}

export default FirstLastPost

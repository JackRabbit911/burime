import { useUnit } from "effector-react"
import TextInput from "../reused/TextInput"
import { $branch, titleChanged } from "../store/branch"

const TitleInput = () => {
  const { title } = useUnit($branch)
  const alert = title ? '' : 'Required field'

  return (
    <fieldset className="fieldset">
      <TextInput
        label="Title"
        value={title || ''}
        placeholder="Введите название произведения"
        optional="Up to 8 words"
        alert={alert}
        onChange={titleChanged}
      />
    </fieldset>
  )

}

export default TitleInput

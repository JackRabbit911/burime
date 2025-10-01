import TextInput from "reused/reactHookForms/TextInput"

const FormTitle = () => (
  <TextInput
    label="Title"
    fieldName="title"
    optional="Up to 8 words"
    placeholder="Введите название произведения"
  />
);

export default FormTitle;

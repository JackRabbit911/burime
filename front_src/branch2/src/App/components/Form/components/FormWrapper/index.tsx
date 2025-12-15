import type { ReactNode } from "react";
import { useFormContext } from "react-hook-form";
import Wrapper from "reused/Wrapper";

type Props = {
  children: ReactNode;
};

const FormWrapper = ({ children }: Props) => {
  const { watch } = useFormContext();
  const [id, title] = watch(['id', 'title']);

  const formTitle = [
    id ? 'Edit book' : 'Create book',
    title,
  ].filter(Boolean).join(': ');

  return (
    <Wrapper title={formTitle}>
      {children}
    </Wrapper>
  );
};

export default FormWrapper;

import { type PaginationButton } from "./utils";

type Props = PaginationButton & {
  setPageNumber: (pageNumber: number) => void;
}

const Button = ({ pageNumber, isActive, label, setPageNumber }: Props) => {
  const className = `join-item btn btn-sm ${isActive ? ' btn-active' : ''}`

  const onSetPageNumber = () => {
    setPageNumber(pageNumber)
  }

  return (
    <button className={className} onClick={onSetPageNumber}>
      {label}
    </button>
  )
}

export default Button

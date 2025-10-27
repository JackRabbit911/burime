import type { PaginationButton } from "./types";

type Props = PaginationButton & {
  setPageNumber: (page: number) => void;
}

const Button = ({ page, isActive, label, setPageNumber }: Props) => {
  const className = `join-item btn btn-sm${isActive ? ' btn-active' : ''}`

  const onSetPageNumber = () => {
    setPageNumber(page)
  }

  return (
    <button
      className={className}
      disabled={isActive}
      onClick={onSetPageNumber}
    >
      {label}
    </button>
  )
}

export default Button

type Props = {
  perPages?: number[];
  limit?: number;
  setPerPage: (perPage: number) => void;
}

const PerPage = ({
  perPages = [25, 50, 100],
  limit,
  setPerPage,
}: Props) => {
  const onSetPerPage = (count: number) => () => setPerPage(count)

  const getClassName = (count: number) =>
    `join-item btn btn-sm ${limit === count ? 'btn-active' : ''}`

  return (
    <div className="join">
      <span className="me-2 pt-2 text-xs">На странице</span>
      {perPages.map(
        (count, key) => (
          <button
            className={getClassName(count)}
            onClick={onSetPerPage(count)}
            disabled={limit === count}
            key={key}
          >
            {count}
          </button>
        )
      )}
    </div>
  )
}

export default PerPage

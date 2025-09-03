import { useUnit } from "effector-react"
import Paginator from "../../reused/Paginator"
import PerPage from "../../reused/Paginator/PerPage"
import { $authorsCount, $authorsPagination, authorsLimitChanged, authorsPageChanged } from "../../store/authors"

const Pagination = () => {
  const total = useUnit($authorsCount)
  const {page, limit} = useUnit($authorsPagination)

  return (
    <div className="flex justify-between mt-2">
      <Paginator
        total={total}
        page={page}
        limit={limit}
        setPage={authorsPageChanged}
      />
      <PerPage
        limit={limit}
        setPerPage={authorsLimitChanged}
      />
    </div>
  )
}

export default Pagination

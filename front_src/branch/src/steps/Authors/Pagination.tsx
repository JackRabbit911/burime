import Paginator from "../../reused/Paginator"
import PerPage from "../../reused/Paginator/PerPage"
import { authorsLimitChanged, authorsPageChanged } from "../../store/authors"

const Pagination = () => {
  return (
    <div className="flex justify-between mt-2">
      <Paginator
        total={15}
        page={1}
        limit={4}
        setPage={authorsPageChanged}
      />
      <PerPage
        setPerPage={authorsLimitChanged}
      />
    </div>
  )
}

export default Pagination

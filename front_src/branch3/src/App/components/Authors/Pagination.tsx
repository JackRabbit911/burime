import { perPages } from "constants"
import { useUnit } from "effector-react"
import { useEffect } from "react"
import { useFormContext } from "react-hook-form"
import Paginator from "reused/Paginator"
import PerPage from "reused/Paginator/PerPage"
import { $total, getAuthorsFx } from "store/authors"

const Pagination = () => {
  const { getValues, setValue } = useFormContext();
  const total = useUnit($total)
  const authorsPayload = getValues('authorsPayload')

  const setPage = (page: number) => {
    authorsPayload.page = page
    setValue('authorsPayload', authorsPayload, { shouldValidate: true, shouldDirty: true })
  }

  const setLimit = (limit: number) => {
    authorsPayload.limit = limit
    authorsPayload.page = 1
    setValue('authorsPayload', authorsPayload, { shouldValidate: true, shouldDirty: true })
  }

  useEffect(() => {
    getAuthorsFx(authorsPayload)
  }, [authorsPayload])

  return (
    <div className="flex justify-between mt-2">
      <Paginator
        total={total}
        page={authorsPayload.page}
        limit={authorsPayload.limit}
        setPage={setPage}
      />
      <PerPage
        perPages={perPages}
        limit={authorsPayload.limit}
        setPerPage={setLimit}
      />
    </div>
  )
}

export default Pagination

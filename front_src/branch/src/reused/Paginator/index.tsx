import { getPaginationData } from "./utils";
import Button from "./Button";

export type Props = {
  total: number;
  page: number;
  limit: number;
  setPage: (page: number) => void;
}

const Paginator = (props: Props) => {
  const onSetPage = (pageNumber: number) => {
    props.setPage(pageNumber)
  }

  const paginationData = getPaginationData(props)

  return (
    <div className="join">
      {paginationData.map(
        (pb, key) => {
          return paginationData.length > 1
            ? <Button
                key={key}
                {...pb}
                setPageNumber={onSetPage}
              />
            : ''
        }
      )}
    </div>
  )
}

export default Paginator

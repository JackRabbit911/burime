import { useUnit } from "effector-react"
import { $authorsFilter, authorsFilterChanged } from "../../store/authors"

const AuthorsFilter = () => {
  const authorsFilter = useUnit($authorsFilter)

  const filterChange = (filter: string) => () => {
    authorsFilterChanged(filter)
  }

  return (
    <div className="filter">
      <input className="btn btn-sm filter-reset"
        type="radio"
        name="metaframeworks"
        aria-label="All"
        checked={authorsFilter === ''}
        onChange={filterChange('')}
      />
      <input
        className="btn btn-sm"
        type="radio"
        name="metaframeworks"
        aria-label="Friends"
        onChange={filterChange('friends')}
        checked={authorsFilter === 'friends'}
      />
      <input className="btn btn-sm"
        type="radio"
        name="metaframeworks"
        aria-label="Favorites"
        onChange={filterChange('favorites')}
        checked={authorsFilter === 'favorites'}
      />
      <input className="btn btn-sm"
        type="radio"
        name="metaframeworks"
        aria-label="Addressbook"
        onChange={filterChange('addressbook')}
        checked={authorsFilter === 'addressbook'}
      />
    </div>
  )
}

export default AuthorsFilter

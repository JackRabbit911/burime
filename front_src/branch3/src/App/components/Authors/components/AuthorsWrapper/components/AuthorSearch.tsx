import { ErrorMessage } from "@hookform/error-message";
import { useFormContext, type FieldError, type FieldErrorsImpl, type Merge } from "react-hook-form";
import { getAuthorsFx } from "store/authors";
import { setAuthorsPayload } from "../../../../Form/utils";
import type { AuthorsFilters } from "schema/input";

type ErrorPayload = {
  search: FieldError | Merge<FieldError, FieldErrorsImpl<any>>;
  filter: FieldError | Merge<FieldError, FieldErrorsImpl<any>>;
}

type Props = {
  filters: AuthorsFilters,
}

const AuthorSearch = ({ filters }: Props) => {
  const { register, getValues, setValue, clearErrors, formState: { errors } } = useFormContext();

  const filterFn = 'authorsPayload.filter'
  const searchFn = 'authorsPayload.search'
  const err = errors?.authorsPayload as ErrorPayload
  const placeholder = "Search"

  const inputClassName =
    !err?.search ?
      "input join-item w-full" :
      "input join-item w-full input-error"

  const selectClassName =
    !err?.filter ?
      "select join-item w-1/5" :
      "select join-item w-1/5 select-error"

  const alert = !err ? null : <ErrorMessage
    as="div"
    name={err.search ? searchFn : filterFn}
    errors={errors}
    className="fieldset-label text-error"
  />

  const onSearch = () => {
    const authorsPayload = getValues('authorsPayload')
    getAuthorsFx(authorsPayload)
  }

  const onReset = () => {
    const limit = getValues('authorsPayload')?.limit
    setValue('authorsPayload', setAuthorsPayload(limit))
    // const authorsPayload = getValues('authorsPayload')
    // getAuthorsFx(authorsPayload)
    clearErrors('authorsPayload')
  }

  return (
    <fieldset className="fieldset">
      <legend className="fieldset-legend flex justify-between w-full">
        <div>Search & filter</div>
        {alert}
      </legend>
      <div className="join w-full">
        <input
          className={inputClassName}
          placeholder={placeholder}
          {...register(searchFn)}
        />
        <select
          className={selectClassName}
          defaultValue=""
          {...register(filterFn)}
        >
          <option value="" disabled>Filter</option>
          {filters.map((item, key) => (
            <option key={key} value={item}>{item}</option>
          ))}
        </select>
        <button
          className="btn btm-sm join-item"
          onClick={onReset}
        >
          X
        </button>
        <button
          className="btn btm-sm join-item"
          disabled={Boolean(err)}
          onClick={onSearch}
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M18.031 16.6168L22.3137 20.8995L20.8995 22.3137L16.6168 18.031C15.0769 19.263 13.124 20 11 20C6.032 20 2 15.968 2 11C2 6.032 6.032 2 11 2C15.968 2 20 6.032 20 11C20 13.124 19.263 15.0769 18.031 16.6168ZM16.0247 15.8748C17.2475 14.6146 18 12.8956 18 11C18 7.1325 14.8675 4 11 4C7.1325 4 4 7.1325 4 11C4 14.8675 7.1325 18 11 18C12.8956 18 14.6146 17.2475 15.8748 16.0247L16.0247 15.8748Z"></path></svg>
        </button>
      </div>
    </ fieldset>
  )
}

export default AuthorSearch

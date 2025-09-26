import CheckBox from "reused/reactHookForms/CheckBox";

const Genres = () => {
  const alert = '123'

  return (
    <fieldset className="fieldset">
      <legend className="fieldset-legend flex justify-end w-full h-8">
        {alert && <span className="label-text text-error">
          {alert}
        </span>}
      </legend>
      {sameWeightGenres.map(
        ({ genres }, key) => (
          <div className="flex flex-row flex-wrap gap-4" key={key}>
            {key > 0 && (
              <div className="divider w-full my-0"></div>
            )}
            {genres.map(({ id, title }, genresKey) => (
              <CheckBox
                fieldName={`genres[${key}][${genresKey}]`}
                label={title}
              />
            ))}
          </div>
        )
      )}
    </fieldset>
  )
}

export default Genres

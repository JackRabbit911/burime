import { useUnit } from "effector-react"
import { $authors, $ownAuthors, masterSelected } from "../store/authors"
import Select, { type Option } from "../reused/Select"
import { $masterId } from "../store/branch"

const Authors = () => {
    const authors = useUnit($authors)
    const ownAuthors = useUnit($ownAuthors)
    const masterId = useUnit($masterId)

    const options: Option[] = ownAuthors.map(
        ({ id, alias }) => ({
            value: id,
            name: alias,
        })
    )

    return (
        <div className="grid md:grid-cols-3 gap-4">
            <fieldset className="fieldset">
                <Select
                    label="Team leader"
                    value={masterId}
                    options={options}
                    onChange={masterSelected}
                />
            </fieldset>
            <div className="md:col-span-2">
                {authors.map((item) => item.alias).join(', ')}
            </div>
        </div>
    )
}

export default Authors

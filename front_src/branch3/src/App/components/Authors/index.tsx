import { useUnit } from "effector-react"
import { useEffect } from "react"
import { $authors, getAuthorsFx } from "store/authors"

const Authors = () => {
    const authors = useUnit($authors)

    useEffect(() => {
        getAuthorsFx()
    }, [])

    console.log(authors)

    return (
        <>rere</>
    )
}

export default Authors

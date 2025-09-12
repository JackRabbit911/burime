import { useUnit } from "effector-react"
import Textarea from "reused/Textarea"
import { $branch, $posts, firstPostChanged, lastPostChanged } from "store"

const FirstLastPost = () => {
    const {first, last} = useUnit($posts)
    const { id } = useUnit($branch)

    return (
        <fieldset className="fieldset md:col-span-2">
             <Textarea
              label="First post"
              placeholder="Напишите что-нибудь"
              value={first.body}
              rows={6}
              disabled={Boolean(id)}
              onChange={firstPostChanged}
            />
            <Textarea
              label="Last post"
              value={last.body}
              placeholder="Если хотите, напишите последние строки вашего произведения"
              rows={6}
              onChange={lastPostChanged}
            />
        </fieldset>
    )
}

export default FirstLastPost

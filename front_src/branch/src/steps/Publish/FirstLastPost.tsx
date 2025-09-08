import { useUnit } from "effector-react"
import Textarea from "../../reused/Textarea"
import { $posts, firstPostChanged, lastPostChanged } from "../../store/posts"
import { $branch } from "../../store/branch"

const FirstLastPost = () => {
    const {first, last} = useUnit($posts)
    const { id } = useUnit($branch)

    return (
        <fieldset className="fieldset md:col-span-2">
             <Textarea
              label="First post"
              placeholder="Напишите что-нибудь"
              value={first}
              rows={6}
              disabled={Boolean(id)}
              onChange={firstPostChanged}
            />
            <Textarea
              label="Last post"
              value={last}
              placeholder="Если хотите, напишите последние строки вашего произведения"
              rows={6}
              onChange={lastPostChanged}
            />
        </fieldset>
    )
}

export default FirstLastPost

import { t } from "i18n/utils"
import type { Member } from "schema/authors"
import { memberIdSetted } from "store/authors"

type Props = {
  members: Member[];
  authorId: number;
}

const Participants = ({ members, authorId}: Props) => {
  return (
    <>
      <h3>
        {t('Participants')}
      </h3>
      {members.map(
        (author: Member) => (
          <button
            key={author.id}
            className="btn btn-soft btn-sm"
            disabled={author.id === authorId}
            onClick={() => {
              memberIdSetted(author.id)
            }}
          >
            {author.alias}
          </button>
        )
      )}
    </>
  )
}

export default Participants

import { useFormContext } from "react-hook-form";
import type { Member, OwnAuthors } from "schema/authors"
import InvitedAuthors from "./InvitedAuthors";
import { memberIdSetted } from "store/authors";
import { changeMaster, getSimpleMembers } from "../../../utils";
import { getMasterAlias } from "App/utils";
import { useEffect } from "react";
import { t } from "i18n/utils";

type Props = {
  ownAuthors: OwnAuthors;
}

const Members = ({ ownAuthors }: Props) => {
  const { setValue, watch } = useFormContext()
  const masterId = Number(watch('masterId'))
  const members = watch('members')
  const authors = getSimpleMembers(members, ownAuthors)
  const masterAlias = getMasterAlias(ownAuthors, masterId)

  useEffect(() => {
    const newMembers = changeMaster(members, ownAuthors, masterId)
    setValue('members', newMembers)
  }, [masterId])

  return (
    <>
      <div>
        <legend className="fieldset-legend">
          <span>{t('Pemissions & status')}</span>
        </legend>
      </div>
      <div className="flex flex-col gap-2">
        <button className="btn btn-soft btn-outline btn-xs"
          onClick={() => memberIdSetted(masterId)}
        >
          {masterAlias}
        </button>
          {authors.map(
            (author: Member) => (
              <InvitedAuthors author={author} key={author.id} />
            )
          )}
      </div>
    </>
  )
}

export default Members

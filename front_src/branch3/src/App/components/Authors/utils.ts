import type { Author, Member } from "schema/authors"

export const isInvited = (
    array: Member[],
    id: number,
): boolean => (
    Boolean(
        array.find((elem: Member) => elem.id === id)
    )
)

export const addNewMember = (members: Member[], author: Author) => {
   const newMember = {
        id: author.id,
        role: 50,
        status: 70,
        alias: author.alias,
    }

    return [...members, newMember]
}

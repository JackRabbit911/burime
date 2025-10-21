import type { Author, BranchAuthor } from "schema/authors"

export const isInvited = (
    array: BranchAuthor[],
    id: number,
): boolean => (
    Boolean(
        array.find((elem: BranchAuthor) => elem.id === id)
    )
)

export const addNewMember = (members: BranchAuthor[], author: Author) => {
   const newMember = {
        id: author.id,
        role: 50,
        status: 70,
        alias: author.alias,
    }

    return [...members, newMember]
}

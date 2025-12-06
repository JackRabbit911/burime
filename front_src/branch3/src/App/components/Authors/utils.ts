import type { Author, Member, OwnAuthors } from "schema/authors"

export const isInvited = (
    array: Member[],
    id: number,
): boolean => (
    Boolean(
        array.find((elem: Member) => elem.id === id)
    )
)

export const addNewMember = (
    members: Member[],
    author: Author,
    role: number = 1,
    status: number = 110
) => {
    const newMember = {
        id: author.id,
        role: role,
        status: status,
        alias: author.alias,
    }

    return [...members, newMember]
}

export const getCurrentMember = (members: Member[], id: number): Member | null => {
    if (members.length === 0) {
        return null
    }

    const currentMembersArray = members.filter(
        (author: Member) => author.id === id
    )

    return currentMembersArray.length > 0
        ? currentMembersArray[0]
        : null
}

export const getSimpleMembers = (members: Member[], ownAuthors: OwnAuthors) => {
    const ids = ownAuthors.map((val) => val.id)
    return members.filter((val) => (!ids.includes(val.id)))
}

export const changeMaster = (members: Member[], ownAuthors: OwnAuthors, masterId: number) => {
    const ownAuthorsIds: number[] = []
    let masterAlias = ''

    ownAuthors.forEach((ownAuthor) => {
        ownAuthorsIds.push(ownAuthor.id)

        if (ownAuthor.id === Number(masterId)) {
            masterAlias = ownAuthor.alias
        }
    })

    const result = members.map((member) => {
        if (ownAuthorsIds.includes(member.id)) {
            member.id = Number(masterId)
            member.alias = masterAlias
        }

        return member
    })

    if (members.length === 0) {
        result.push({
            id: masterId,
            role: 255,
            status: 200,
            alias: masterAlias,
        })
    }

    return result;
}

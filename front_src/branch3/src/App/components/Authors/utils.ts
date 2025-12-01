import type { Author, Member, OwnAuthors } from "schema/authors"
import { isPermission, memberStatus, permissions } from "./permissions"

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
    role: number = permissions.WRITE,
    status: number = memberStatus.invited
) => {
   const newMember = {
        id: author.id,
        role: role,
        status: status,
        alias: author.alias,
    }

    return [...members, newMember]
}

export const  getCurrentMember = (members: Member[], id: number): Member | null => {
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

    members.map((member) => {
        if (ownAuthorsIds.includes(member.id)) {
            member.id = Number(masterId)
            member.alias = masterAlias
        }

        return member
    })
}

export const isModerator = (member: Member | null): boolean =>
    isPermission(member?.role || 0, permissions.MANAGE) &&
    isPermission(member?.role || 0, permissions.MODERATE)


export const isAccept = (member: Member | null): boolean =>
    (member?.status || 0) === memberStatus.candidate ||
    (member?.status || 0) === memberStatus.denied

export const isBan = (member: Member | null): boolean =>
    (member?.status || 0) === memberStatus.member &&
    !isPermission(member?.role || 0, permissions.MANAGE) 


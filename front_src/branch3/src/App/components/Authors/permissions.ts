export const permissions = {
    'WRITE': 1 << 0,
    'EDIT_POST': 1 << 1,
    'DESIGN': 1 << 2,
    'DIRECTOR': 1 << 3,
    'MODERATE': 1 << 4,
    'MANAGE': 1 << 5,
    'EDIT_BRANCH': 1 << 6,
    'EDIT_STATUS': 1 << 7,
}

export const memberStatus = { 
    'deleted': 50,
    'denied': 70,
    'refused': 80,
    'candidate': 90,
    'invited': 110,
    'invited_informed': 120,
    'member': 200,
}

export const isPermission = (role: number, permission: number) => (role & permission) !== 0 ? true : false
export const moderatorPerm = permissions.MANAGE | permissions.MODERATE | permissions.WRITE
export const getStatus = (status: number) => (
    Object.entries(memberStatus).reduce((acc, [key, value]) => {
        if (value === status) {
            acc = key
        }

        return acc
    }, 'unkown')
)

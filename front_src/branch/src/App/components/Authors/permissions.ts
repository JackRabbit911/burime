import type { Statuses } from "schema/input"

export const isPermission = (role: number, permission: number) => (role & permission) !== 0 ? true : false

export const getStatusString = (statusObj: Statuses, status: number) => (
    Object.entries(statusObj).reduce((acc, [key, value]) => {
        if (value === status) {
            acc = key
        }

        return acc
    }, 'unkown')
)

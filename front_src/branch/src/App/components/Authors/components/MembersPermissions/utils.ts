import type { Member } from "schema/authors";
import type { Permissions, Statuses } from "schema/input";
import { isPermission } from "../../permissions";

export class buttonEnabled {
    readonly permissions: Permissions;
    readonly statuses: Statuses;
    status: number;
    role: number;

    constructor (permissions: Permissions, statuses: Statuses, member: Member | null) {
        this.permissions = permissions
        this.statuses = statuses
        this.role = member?.role || 0
        this.status = member?.status || 0
    }

    moderator (): boolean {
        return !(
            isPermission(this.role, this.permissions.MODERATE) &&
            isPermission(this.role, this.permissions.MANAGE)
        )
    }

    accept (): boolean {
        return (
            this.status === this.statuses.candidate ||
            this.status === this.statuses.denied
        )
    }

    deny (): boolean {
        return (
            this.status === this.statuses.candidate
        )
    }

    ban (): boolean {
        return (
            this.status === this.statuses.member &&
            !isPermission(this.role, this.permissions.MANAGE) &&
            !isPermission(this.role, this.permissions.EDIT_STATUS)
        )
    }

    delete (): boolean {
        return (
            this.status < this.statuses.member
        )
    }
}

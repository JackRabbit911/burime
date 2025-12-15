import type { FieldValues } from "react-hook-form";
import type { OwnAuthors } from "schema/authors";

const readyCover = (values: FieldValues): boolean => (
    values.branch.info.cover
    || values.branch.info.bgImg
    || values.branch.info.bg_color !== '#eeeeee'
    || values.branch.info.text_color !== '#333333'
)

export const readyProgress = (values: FieldValues) => {
    const t = values.branch.title ? 35 : 0
    const g = values.branch_genres.length > 0 ? 20 : 0
    const m = values.members.length > 0 ? 20 : 0
    const d = values.branch.info.description ? 10 : 0
    const r = values.branch.info.rules ? 10 : 0
    const c = readyCover(values) ? 5 : 0 

    return t + g + m + d + r + c
}

export const getAlerts = (values: FieldValues): string[] => {
    const result = []

    if (!values.branch.title) {
        result.push('Title is required')
    }

    if (values.branch_genres.length === 0) {
        result.push('You need to choose at least one genre')
    }

    if (values.members.length === 0) {
        result.push('You need to choose at least team leader')
    }

    if (!values.branch.info.description) {
        result.push('Create a description for Your work')
    }

    if (!values.branch.info.rules) {
        result.push('Formulate the private rules of this branch')
    }

    if (!readyCover(values)) {
        result.push('Design Your book cover')
    }

    return result
}

export const isReady = (values: FieldValues): boolean => (
    values.branch.title && values.branch_genres.length > 0 && values.members.length > 0
)

export const getMasterAlias = (ownAuthors: OwnAuthors, masterId: number) => (
    ownAuthors.reduce((acc, value) => (value.id === Number(masterId) ? value.alias : acc), '')
)

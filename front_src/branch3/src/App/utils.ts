import type { FieldValues } from "react-hook-form";
import type { OwnAuthors } from "schema/authors";

const readyCover = (values: FieldValues): boolean => (
    values.info.cover
    || values.info.bgImg
    || values.info.bg_color !== '#eeeeee'
    || values.info.text_color !== '#333333'
)

export const readyProgress = (values: FieldValues) => {
    const t = values.title ? 35 : 0
    const g = values.genres.length > 0 ? 20 : 0
    const m = values.members.length > 0 ? 20 : 0
    const d = values.info.description ? 10 : 0
    const r = values.info.rules ? 10 : 0
    const c = readyCover(values) ? 5 : 0 

    return t + g + m + d + r + c
}

export const getAlerts = (values: FieldValues): string[] => {
    const result = []

    if (!values.title) {
        result.push('Title is required')
    }

    if (values.genres.length === 0) {
        result.push('You need to choose at least one genre')
    }

    if (values.members.length === 0) {
        result.push('You need to choose at least team leader')
    }

    if (!values.info.description) {
        result.push('Create a description for Your work')
    }

    if (!values.info.rules) {
        result.push('Formulate the private rules of this branch')
    }

    if (!readyCover(values)) {
        result.push('Design Your book cover')
    }

    return result
}

export const isReady = (values: FieldValues): boolean => (
    values.title && values.genres.length > 0 && values.members.length > 0
)

export const getMasterAlias = (ownAuthors: OwnAuthors, masterId: number) => (
    ownAuthors.reduce((acc, value) => (value.id === Number(masterId) ? value.alias : acc), '')
)

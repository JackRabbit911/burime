import type { FieldValues } from "react-hook-form";

const readyCover = (values: FieldValues): boolean => (
    values.cover
    || values.bgImg
    || values.bg_color !== '#eeeeee'
    || values.text_color !== '#333333'
)

export const readyProgress = (values: FieldValues) => {
    const t = values.branchTitle ? 40 : 0
    const g = values.genres.length > 0 ? 30 : 0
    const d = values.description ? 15 : 0
    const r = values.rules ? 10 : 0
    const c = readyCover(values) ? 5 : 0 

    return t + g + d + r + c
}

export const getAlerts = (values: FieldValues): string[] => {
    const result = []

    if (!values.branchTitle) {
        result.push('Title is required')
    }

    if (values.genres.length === 0) {
        result.push('You need to choose at least one genre')
    }

    if (!values.description) {
        result.push('Create a description for Your work')
    }

    if (!values.rules) {
        result.push('Formulate the private rules of this branch')
    }

    if (!readyCover(values)) {
        result.push('Design Your book cover')
    }

    return result
}

export const isReady = (values: FieldValues): boolean => (
    values.branchTitle && values.genres.length > 0
)

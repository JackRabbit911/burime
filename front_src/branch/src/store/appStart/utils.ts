import { firstSegsRegular, idRegular } from "./constants";

export const getIdWithValidation = () => {
    const pathname = window.location.pathname
    const id = pathname.replace(firstSegsRegular, '')

    return {
        id,
        success: Boolean(!id || idRegular.test(id))
    }
}

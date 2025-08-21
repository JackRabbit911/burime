import type { Props } from ".";
import type { PaginationButton } from "./types";

const ellipsisLeft = (pageNumber: number, lastPage: number) => {
    let max = 0

    switch (lastPage - pageNumber) {
        case 0:
            max = pageNumber - 4
            break
        case 1:
            max = pageNumber - 3
            break
        case 2:
            max = pageNumber - 2
            break
        default:
            max = pageNumber - 1
    }

    return Math.ceil((1 + max)/2)
}

const ellipsisRight = (pageNumber: number, lastPage: number) => {
    let min = 0

    if (pageNumber < 5) {
        min = 5
    } else {
        min = pageNumber + 1
    }

    return Math.floor((min + lastPage)/2)
}

const short = (pageNumber: number, lastPage: number) => {
    let result = []

    for (let i = 0; i < lastPage;) {
        result[i] = [true, ++i, pageNumber === i ? true : false]
    }

    return result
}


const getSource = (pageNumber: number, total: number, perPage: number) => {
    const lastPage = Math.ceil(total/perPage)

    return lastPage <= 7 ? short(pageNumber, lastPage) :
    [
        [pageNumber > 1, 1, false],
        [
            pageNumber > 2,
            ellipsisLeft(pageNumber, lastPage),
            false, 
            pageNumber < 5 ? 2 : '...'
        ],
        [(lastPage - pageNumber) < 1, pageNumber - 4, false],
        [(lastPage - pageNumber) < 2, pageNumber - 3, false],
        [(lastPage - pageNumber) < 3, pageNumber - 2, false],
        [(pageNumber - 1) > 2, pageNumber - 1, false],

        [true, pageNumber, true],

        [(pageNumber + 1) < lastPage, pageNumber + 1, false],
        [pageNumber < 4 && (pageNumber + 2) < lastPage, pageNumber + 2, false],
        [pageNumber < 3 && (pageNumber + 3) < lastPage, pageNumber + 3, false],
        [pageNumber < 2 && (pageNumber + 4) < lastPage, pageNumber + 4, false],

        [
            (lastPage - pageNumber) >= 3 && (pageNumber + 2) > 1,
            ellipsisRight(pageNumber, lastPage),
            false,
            (lastPage - pageNumber) < 4 ? lastPage - 1 : '...',
        ],
        [(lastPage - pageNumber) >= 1, lastPage, false],
    ]
}

export const getPaginationData = ({ page, total, limit }: Props) =>
    getSource(page, total, limit)
        .filter((pageArray) => pageArray[0])
        .map((pageArray) => ({
            page: pageArray[1],
            isActive: pageArray[2],
            label: pageArray[3] ?? pageArray[1]
        })) as unknown as PaginationButton[]

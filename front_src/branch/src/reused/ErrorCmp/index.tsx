type Props = {
    status: number;
}

const reasonPhrase = (status: number) => {
    switch (status) {
        case 401:
            return '401 | Unauthorized'
        case 403:
            return '403 | Forbidden'
        case 404:
            return '404 | Not found'
        case 500:
            return '500 | Internal server error'
        case 503:
            return '503 | Service Unavailable'
        default:
            return `${status} | Хз, что это...`
    }
}

const ErrorCmp = ({ status }: Props) => {
    console.log(status)
    return (
        <div className="h-96 flex flex-col justify-center">
            <h1 className="text-center text-3xl">
                {reasonPhrase(status)}
            </h1>
        </div>
    )
}

export default ErrorCmp

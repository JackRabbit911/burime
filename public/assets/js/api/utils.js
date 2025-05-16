function logger(message) {
    const logger = document.getElementById('logger')
    const msg = document.createElement('div')
    msg.append(`${new Date().toISOString()}: ${message}`)

    logger.append(msg)
}

function navigateTo(uri) {
    window.location.assign(localBaseUrl + uri)
}

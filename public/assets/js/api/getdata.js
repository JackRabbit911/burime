const getConfigExtension = (tokenType) => ({
    headers: {
        'Authorization': tokenType + ' ' + sessionStorage.getItem(tokenType)
    }
})

async function getData(url, tokenType = 'Bearer') {
    const response = await api.get(url, getConfigExtension(tokenType))

    if (response.headers.has('X-Token') && response.headers.get('X-Token') == 'Refresh') {
        return getData(url, 'Refresh')
    } else {
        return response.data
    }
}

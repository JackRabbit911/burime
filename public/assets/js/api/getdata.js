const host = 'http://127.0.0.1:5500/site.zone/www/public/api/'

async function getData(url, tokenType = 'Bearer') {
    const token = sessionStorage.getItem(tokenType)

    if (!token) {
        window.location.assign(host + 'login.html')
    }

    const response = await fetch(url, {
        headers: {
            'Content-Type': 'application/json',
            'Authorization': tokenType + ' ' + token
        }
    })

    if (response.status == 401) {
        window.location.assign(host + 'login.html')
    }

    if (response.headers.has('Bearer')) {
        sessionStorage.setItem('Bearer', response.headers.get('Bearer'))
    }

    if (response.headers.has('X-Token') && response.headers.get('X-Token') == 'Refresh') {
        return getData(url, 'Refresh')
    } else {
        const json = await response.json()
        return json
    }
}

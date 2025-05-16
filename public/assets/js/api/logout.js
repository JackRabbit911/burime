async function logout () {
    const refresh = sessionStorage.getItem('Refresh')

    await api.delete('/test/logout', {
        headers: {
            'X-Token': refresh
        }
    })

    console.log(refresh);

    sessionStorage.clear()
    navigateTo('/login.html')
}

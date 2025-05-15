async function logout () {
    const refresh = sessionStorage.getItem('Refresh')

    await api.delete('/test/logout', {
        headers: {
            'Refresh': refresh
        }
    })

    sessionStorage.clear()
    navigateTo('/login.html')
}

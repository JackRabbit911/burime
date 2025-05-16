const api = axios.create({
    baseURL: remoteBaseURL,
    timeout: 1000,
    headers: { 'Content-Type': 'application/json' }
});

api.interceptors.request.use(function (config) {
    const token = sessionStorage.getItem('Bearer')

    if (!token) {
        navigateTo('/login.html')
    }

    return config;
}, function (error) {
    return Promise.reject(error);
});

api.interceptors.response.use(function (response) {
    if (response.headers.has('Bearer')) {
        sessionStorage.setItem('Bearer', response.headers.get('Bearer'))
    }
    return response;
}, function (error) {
    if (error.status == 401) {
        navigateTo('/login.html')
    }

    return Promise.reject(error);
});

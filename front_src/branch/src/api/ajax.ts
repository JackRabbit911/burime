import axios, { AxiosError } from "axios";

const lang = document.querySelector('html')?.getAttribute('lang')

const ajax = axios.create({
    baseURL: `/api`,
    timeout: 1000,
    headers: {
        'Accept-Language': lang,
        'Content-Type': 'application/json',
    }
});

export default ajax;

ajax.interceptors.response.use(
    (response) => response,
    (error) => {
    if (error instanceof AxiosError) {
        if (error.status === 403) {
            window.location.reload()
        }
    }
    console.log(error)
})

import axios, { AxiosError } from "axios";

const lang = document.querySelector('html')?.getAttribute('lang');
const { protocol, hostname } = window.location
export const host = `${protocol}//${hostname}`

const ajax = axios.create({
    baseURL: `${host}/api`,
    timeout: 10000,
    headers: {
        'Accept-Language': lang,
        'Content-Type': 'application/json',
    },
});

ajax.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error instanceof AxiosError) {
            if (error.status === 401) {
                if (hostname === 'localhost') {
                    window.location.href = `${host}:80`
                } else {
                    window.location.reload();
                }
            }
        }
        
        return Promise.reject(error);
    },
);

export default ajax;

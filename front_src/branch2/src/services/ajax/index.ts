import axios, { AxiosError } from "axios";

const lang = document.querySelector('html')?.getAttribute('lang');

const ajax = axios.create({
    baseURL: `/api`,
    timeout: 1000,
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
                window.location.reload();
            }
        }
        
        return Promise.reject(error);
    },
);

export default ajax;

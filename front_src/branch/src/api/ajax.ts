import axios from "axios";

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

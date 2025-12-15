import * as z from "zod"

const MAX_UPLOAD_SIZE = 1024 * 1024 * 2; // 2MB
const ACCEPTED_IMAGE_TYPES = ["image/jpeg", "image/png", "image/gif"]

export const imageFile = z.file()
    .max(MAX_UPLOAD_SIZE)
    .mime(ACCEPTED_IMAGE_TYPES)
    .nullable()

export function base64ToFile(data: string | null, filename: string) {
    if (!data) {
        return null
    }

    const arr = data.split(',')
    const f = arr[0].match(/:(.*?);/)
    const mime = f ? f[1] : ''
    const ext = mime.split('/')[1].replace(/;/, '')
    const bstr = atob(arr[arr.length - 1])
    const len = bstr.length
    const bytes = new Uint8Array(len);

    for (let i = 0; i < len; i++) {
        bytes[i] = bstr.charCodeAt(i);
    }

    const blob = new Blob([bytes], { type: mime });

    return new File([blob], filename + '.' + ext, { type: mime, lastModified: Date.now() });
}

export async function fileToBase64(file: File | null) {
    if (!file) {
        return null
    }

    return new Promise((resolve, reject) => {
        const reader = new FileReader();

        reader.onload = () => resolve(reader.result);
        reader.onerror = (error) => reject(error);
        reader.readAsDataURL(file);
    });
}

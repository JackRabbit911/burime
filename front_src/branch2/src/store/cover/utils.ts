import type { CoverFile } from "../bootstrap/types";

export function base64ToFile(data: CoverFile | null) {
    if (!data) {
        return null
    }
    
    const binaryString = atob(data.base64);

    const len = binaryString.length;
    const bytes = new Uint8Array(len);
    
    for (let i = 0; i < len; i++) {
        bytes[i] = binaryString.charCodeAt(i);
    }

    const blob = new Blob([bytes], { type: data.mime });

    return new File([blob], data.filename, { type: data.mime, lastModified: Date.now() });
}

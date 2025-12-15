import type { Message } from "./types";

export const isValidMessage = (
    message?: Message,
) => Boolean(
    message?.title ||
    message?.description ||
    message?.component,
);

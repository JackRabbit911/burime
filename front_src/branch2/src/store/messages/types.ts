import type { ReactNode } from "react";

export type MessageAction = {
    label: string;
    onClick?: () => void;
};

export type Message = {
    title?: string,
    description?: string;
    component?: ReactNode;
    actions?: MessageAction[];
    hideCloseButton?: boolean,
};

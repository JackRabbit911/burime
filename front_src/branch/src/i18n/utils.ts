import { $translate, translateKeyAdded } from "./store"

const sprintf = (str: string, ...argv: any[]): string => !argv.length ? str : 
    sprintf(str = str.replace("%", argv.shift()), ...argv);

export const t = (value: string, ...argv: any[]): string => {
    const translate = $translate.getState();

    if (!translate[value]) {
        translateKeyAdded(value)
    }

    return translate[value]
        ? sprintf(translate[value], ...argv)
        : sprintf(value, ...argv)
}

import { createEvent, createStore } from "effector";

export const darkModeChanged = createEvent<boolean>()

export const $darkMode = createStore(initDarkMode())
    .on(darkModeChanged, (_, darkMode) => darkMode)

function initDarkMode () {
    const mq = window.matchMedia('(prefers-color-scheme: dark)');
    mq.addEventListener('change', handleThemeChange);

    return mq.matches
}

function handleThemeChange(this: MediaQueryList, ev: MediaQueryListEvent) {
    darkModeChanged(ev.matches)
}

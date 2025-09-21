import { combine, createEvent, createStore } from "effector";
import { debug } from "patronum";

import {
    branchInit,
    toggleGenre,
    getBranchMasterId,
    numberInfoUpdate,
    textInfoUpdate, 
    toggleInfo,
} from "./utils";
import {
    authorInvited,
    authorRemoved,
    authorRoleToggled,
    masterSelected
} from "../authors";
import { addAuthor, authorRoleChange, removeAuthor, selectMaster } from "../authors/utils";
import { globalReset } from "../common";
import { bgNameCancelled, bgNameRecived, coverNameCancelled, coverNameRecived } from "../cover";

import type { Bootstrap, Branch } from "../bootstrap/types";

// Events
export const branchFromBootstrap = createEvent<Bootstrap>()
export const genreToggled = createEvent<number>()
export const rwModeToggled = createEvent<number>()
export const moderationToggled = createEvent<number>()
export const allowCommentToggled = createEvent<number>()
export const signatureToggled = createEvent<number>()
export const ageLimitChanged = createEvent<number>()
export const postSizeChanged = createEvent<number>()
export const timeLimitChanged = createEvent<number>()
export const titleChanged = createEvent<string>()
export const decriptionChanged = createEvent<string>()
export const rulesChanged = createEvent<string>()
export const bgColorChanged = createEvent<string>()
export const textColorChanged = createEvent<string>()
export const textSizeChanged = createEvent<number>()

// Stores
export const $branch = createStore<Branch>(branchInit())
    .on(branchFromBootstrap, (_, result) => result.branch)
    .on(genreToggled, toggleGenre)
    .on(rwModeToggled, (branch, role) => ({...branch, role}))
    .on(moderationToggled, toggleInfo('moderation'))
    .on(allowCommentToggled, toggleInfo('allow_comments'))
    .on(signatureToggled, toggleInfo('signature'))
    .on(ageLimitChanged, (branch, age_limit) => ({...branch, age_limit}))
    .on(postSizeChanged, numberInfoUpdate('post_size'))
    .on(timeLimitChanged, numberInfoUpdate('time_limit'))
    .on(titleChanged, (branch, title) => ({...branch, title}))
    .on(decriptionChanged, textInfoUpdate('description'))
    .on(rulesChanged, textInfoUpdate('rules'))
    .on(masterSelected, selectMaster)
    .on(authorInvited, addAuthor)
    .on(authorRemoved, removeAuthor)
    .on(authorRoleToggled, authorRoleChange)
    .on(bgColorChanged, textInfoUpdate('bg_color'))
    .on(textColorChanged, textInfoUpdate('text_color'))
    .on(textSizeChanged, numberInfoUpdate('text_size'))
    .on([coverNameRecived, coverNameCancelled], textInfoUpdate('cover'))
    .on([bgNameRecived, bgNameCancelled], textInfoUpdate('bg_img'))
    .reset(globalReset)

export const $isBranchLoaded = createStore(false)
    .on(branchFromBootstrap, () => true)

export const $selectedGenres = combine($branch, (branch) => branch?.genres || [])
export const $selectedRWMode = combine($branch, (branch) => branch?.role || 0)
export const $masterId = combine($branch, getBranchMasterId)

export const $branchAuthors = combine($branch, (branch) => (branch?.authors || []).filter(
    (author) => author.role < 150
))

debug({$branch})

import { combine, createEvent, createStore } from "effector";
import type { Branch } from "../vocabularies/types";
import { getVocabulariesFx } from "../vocabularies";
import { branchInit, calcSelectedGenres, getBranchMasterId, numberInfoUpdate, textInfoUpdate, toggleInfo } from "./utils";
import { authorInvited, masterSelected } from "../authors";
import { addAuthor, selectMaster } from "../authors/utils";
import { debug } from "patronum";

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

export const $branch = createStore<Branch>(branchInit())
    .on(getVocabulariesFx.doneData, (_, data) => data?.result?.branch)
    .on(genreToggled, calcSelectedGenres)
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

export const $selectedGenres = combine($branch, (branch) => branch?.genres || [])
export const $selectedRWMode = combine($branch, (branch) => branch?.role || 0)

export const $masterId = combine($branch, getBranchMasterId)
export const $branchAuthors = combine($branch, (branch) => branch?.authors || [])

debug($branch)

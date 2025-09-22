import { createEvent, createStore } from "effector";

import { globalReset } from "store/common";

import type { Bootstrap, SameWeightGenres } from "./types";
import { getOrderedSameWeightGenres } from "./utils";

// Events
export const genresFromBootstrap = createEvent<Bootstrap>()

// Stores
export const $sameWeightGenres = createStore<SameWeightGenres[]>([])
    .on(genresFromBootstrap, getOrderedSameWeightGenres)
    .reset(globalReset)

export const $bootstrapStatus = createStore(200)

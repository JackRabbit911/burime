import { combine } from "effector";

import { $branch } from "store/branch";
import { getBranchFormTitle, getBranchFormValues } from "./utils";

import { $sameWeightGenres } from "store/bootstrap";

export const $branchFormTitle = combine($branch, getBranchFormTitle);

export const $branchFormValues = combine(
    $branch,
    $sameWeightGenres,
    getBranchFormValues,
)
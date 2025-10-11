import type { Branch } from "schema/input";

export const getDefaults = (branch: Branch) => ({
    branchTitle: branch.title || '',
    genres: branch.genres,
    branchRole: branch.role,
    moderation: Boolean(branch.info.moderation),
    comments: Boolean(branch.info.allow_comments),
    signature: Boolean(branch.info.signature),
    ageLimit: branch.age_limit,
    postSize: branch.info.post_size,
    timeLimit: branch.info.time_limit,
    description: branch.info.description,
    rules: branch.info.rules,
})

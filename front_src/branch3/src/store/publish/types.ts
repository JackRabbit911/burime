import type { Branch } from "../bootstrap/types"
import type { Posts } from "../posts/types";

export type Payload = {
    branch: Branch;
    posts: Posts;
    bg_img: File | null;
    cover: File | null;
}

export type SaveResponse = {
    'branch_id': number;
    'first_id': number;
    'last_id': number;
}

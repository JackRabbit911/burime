import type { Branch } from "../bootstrap/types"
import type { Posts } from "../posts/types";

export type Payload = {
    branch: Branch;
    posts: Posts;
    bg_img: File | null;
    cover: File | null;
}

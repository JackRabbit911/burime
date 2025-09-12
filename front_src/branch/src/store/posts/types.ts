type Post = {
    id: number | null;
    body: string;
}

export type Posts = {
    first: Post;
    last: Post;
}

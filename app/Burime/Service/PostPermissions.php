<?php declare(strict_types=1);

namespace App\Burime\Service;

use App\Author\Author;
use App\Burime\Post;
use Common\Contract\BranchInterface;
use Common\Enum\BranchAuthorPermissions;
use Common\Enum\BranchStatus;
use Common\Enum\PostStatus;

class PostPermissions
{
    public BranchInterface $branch;
    private ?object $user;
    private ?Author $author;

    public function __construct(BranchInterface $branch, ?object $user)
    {
        $this->branch = $branch;
        $this->user = $user;

        $this->author = $branch->authors->getInstance($user->id ?? 0, 'owner');
    }

    public function getAuthor($key = null)
    {
        return ($key) ? $this->author->$key ?? null : $this->author;
    }

    public function getPostAuthor($post)
    {
        return $post->author_id;
    }

    public function isLast($post)
    {
        return $this->branch->maxWeight == $post->weight;
    }

    public function timer(): bool
    {
        if (!$this->user) {
            return false;
        }

        if ($this->branch->status === BranchStatus::Writing->value
        && $this->user->id === $this->branch->info['current_writer']) {           
                return true;
        }
        
        return false;
    }

    public function isAuthor(?Post $post)
    {
        if (!$this->user || !$post) {
            return false;
        }

        $author = $this->user->ownAuthors->getInstance($post->author_id);
        return ($author) ? true : false;
    }

    public function hasRole(int $role)
    {
        if (!$this->author) {
            return false;
        };

        return $this->author->role >= $role;
    }

    public function show($post)
    {
        if ($post->status === PostStatus::Deleted->value) {
            return false;
        }

        if ($post->status <= PostStatus::Draft->value && !$this->isAuthor($post)) {
            return false;
        }

        if ($post->status === PostStatus::Moderation->value) {
            return ($this->isAuthor($post)
                || BranchAuthorPermissions::MODERATE->is($this->author?->role ?? 0));
        }

        return true;
    }

    public function edit($post)
    {
        if ($this->isLast($post)
        && $post->status !== PostStatus::Moderation->value
        && $this->isAuthor($post)
        && $this->branch->info['current_writer'] === $this->user->id) {
            return true;
        }

        return false;
    }

    public function delete($post)
    {
        if (!$post) {
            return false;
        }

        if ($post->status === PostStatus::Fixed->value) {
            return false;
        }

        if ($this->hasRole(BranchAuthorPermissions::MODERATE->value)
        && $post->status < PostStatus::Approved->value) {
            return true;
        }

        if ($this->isLast($post)
        && $this->isAuthor($post)
        && $this->branch->info['current_writer'] === $this->user->id) {
            return true;
        }

        return false;
    }

    public function approve($post)
    {
        if ($post->status === PostStatus::Moderation->value
            && $this->hasRole(BranchAuthorPermissions::MODERATE->value)
            && !$this->isAuthor($post)) {
            return true;
        }

        return false;
    }

    public function write($post)
    {
        if ($this->branch->role > 0 && !$this->author) {
            return false;
        }

        if (!$post) {
            return true;
        }
        
        if (!$this->isAuthor($post) 
            && $this->isLast($post)
            && $post->status >= PostStatus::Publish->value
            && $this->branch->status === BranchStatus::Ready->value
            || $this->branch->status === BranchStatus::Writing->value
            && $this->branch->info['current_writer'] === $this->user->id
            && $this->isLast($post)) {
            return true;
        }

        return false;
    }

    public function rating($post)
    {
        if ($post->status < PostStatus::Publish->value) {
            return false;
        }

        return !$this->isAuthor($post);
    }

    public function comment($post)
    {
        if (!$this->user || $this->user->ownAuthors->empty()
            || $post->status < PostStatus::Publish->value) {
            return false;
        }

        return isset($this->branch->info['comments']) && $this->branch->info['comments'] == 1;
    }

    public function branch($post)
    {
        if (!$this->user || $this->user->ownAuthors->empty()
            || $post->status < PostStatus::Publish->value) {
            return false;
        }

        return true;
    }
}

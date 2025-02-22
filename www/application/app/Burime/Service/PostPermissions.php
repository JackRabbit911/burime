<?php declare(strict_types=1);

namespace App\Burime\Service;

use App\Author\Author;
use Common\Contract\BranchInterface;
use Common\Enum\AuthorRole;

use Common\Enum\BranchRole;
use Common\Enum\BranchStatus;
use Common\Enum\PostStatus;
use Auth\User;

class PostPermissions
{
    public BranchInterface $branch;
    private ?User $user;
    private ?Author $author;

    public function __construct(BranchInterface $branch, ?User $user)
    {
        $this->branch = $branch;
        $this->user = $user;

        $this->author = $branch->authors->getInstance($user->id ?? 0, 'user_id');
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

    public function isAuthor($post)
    {
        if (!$this->user) {
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
        if ($post->status <= PostStatus::Draft->value && !$this->isAuthor($post)) {
            return false;
        }

        if ($post->status === PostStatus::Moderation->value) {
            return ($this->isAuthor($post) || $this->hasRole(AuthorRole::Moderator->value));
        }

        return true;
    }

    public function expired($post)
    {
        return $post->status === 10;
    }

    public function draft($post)
    {
        
    }

    public function edit($post)
    {
        if ($this->isLast($post) && $this->isAuthor($post) 
            && $post->status !== PostStatus::Fixed->value) {
            return true;
        }

        return false;
    }

    public function delete($post)
    {
        if ($post->status === PostStatus::Fixed->value) {
            return false;
        }

        if ($this->isLast($post)) {
            if (($this->hasRole(AuthorRole::Moderator->value) 
                && $this->branch->status === BranchStatus::Blocked->value) 
                || $this->isAuthor($post)) {
                return true;
            }
        }

        if ($this->expired($post)
            && $this->isAuthor($post)) {
            return true;
        }

        return false;
    }

    public function approve($post)
    {
        if ($post->status === PostStatus::Moderation->value && $this->hasRole(AuthorRole::Moderator->value)) {
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
            && $post->status === PostStatus::Publish->value
            && $this->branch->status === BranchStatus::Ready->value) {
            return true;
        }

        return false;
    }

    public function first()
    {
        if (is_null($this->branch->maxWeight)) {
            if ($this->branch->role === BranchRole::Open->value) {
                return true;
            } elseif (!$this->branch->authors->intersect($this->user->ownAuthors)->empty()
                && $this->hasRole(AuthorRole::Author->value)) {
                return true;
            }
        }

        return false;
    }

    public function newPost($last_author)
    {
        // if ($this->user->ownAuthors->has($last_author)) {
        //     return false;
        // }

        // if ($this->branch->status === BranchStatus::READY) {
        //     if ($this->branch->role === BranchRole::Open->value) {
        //         return true;
        //     } elseif (!$this->branch->authors->intersect($this->user->ownAuthors)->empty()
        //         && $this->hasRole(AuthorRole::AUTHOR)) {
        //         return true;
        //     }
        // }

        return false;
    }

    public function save($post)
    {
        
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
            || $post->status !== PostStatus::Publish->value) {
            return false;
        }

        return isset($this->branch->info['comments']) && $this->branch->info['comments'] == 1;
    }

    public function branch($post)
    {
        if (!$this->user || $this->user->ownAuthors->empty()
            || $post->status !== PostStatus::Publish->value) {
            return false;
        }

        return true;
    }
}

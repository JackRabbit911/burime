<?php declare(strict_types=1);

namespace App\Burime;

use App\Burime\Model\ModelPost;
use Common\Contract\PostInterface;
use Sys\Entity\Entity;

#[ModelPost]
class Post extends Entity implements PostInterface
{
    public function __construct()
    {
        if (isset($this->rating)) {
            $this->rating = round((float) $this->rating, 1);
        }
    }
}

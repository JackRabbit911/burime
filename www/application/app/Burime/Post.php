<?php declare(strict_types=1);

namespace App\Burime;

use App\Burime\Model\ModelPost;
use Sys\Entity\Entity;

#[ModelPost]
class Post extends Entity 
{
    public function __construct()
    {
        if (isset($this->rating)) {
            $this->rating = round((float) $this->rating, 1);
        }
    }
}

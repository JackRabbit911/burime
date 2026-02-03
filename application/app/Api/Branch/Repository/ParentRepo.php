<?php

declare(strict_types=1);

namespace App\Api\Branch\Repository;

use App\Api\Branch\Model\ModelAuthors;
use App\Api\Branch\Model\ModelBranch;

abstract class ParentRepo
{
    protected string $prefix;

    public function __construct(
        protected ModelBranch $modelBranch,
        protected ModelAuthors $modelAuthors
    ) {}
    
    public function getOwnAuthors($user_id)
    {
        return $this->modelAuthors->getByUser($user_id);
    }

    public function getTotalGenres()
    {
        $genres = $this->modelBranch->getTotalGenres();

        return array_map(function ($v) {
            $array = json_decode($v);
            usort($array, function ($a, $b) {
                return $a->id < $b->id ? -1 : 1;
            });
            return $array;
        }, $genres);
    }

    public function getBase64Coverfiles(?int $branch_id)
    {
        $data['cover'] = $this->fileTypeEncode($branch_id, 'cover');
        $data['bg_img'] = $this->fileTypeEncode($branch_id, 'background');

        return $data;
    }

    private function fileTypeEncode(?int $branch_id, string $filename)
    {
        if (!$branch_id) {
            return null;
        }

        $pattern = $this->prefix . $branch_id . '/' . $filename . '.{jp*g,png}';
        $file = glob($pattern, GLOB_BRACE)[0] ?? null;

        if (!$file) {
            return null;
        }

        $type = mime_content_type($file);
        $data = file_get_contents($file);
        return 'data:' . $type . ';base64,' . base64_encode($data);
    }
}

<?php

declare(strict_types=1);

namespace App\Branch\Api\Repository;

abstract class ParentRepo
{
    protected string $prefix;
    
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

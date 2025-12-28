<?php

declare(strict_types=1);

namespace App\Api\Common\Helper;

use HttpSoft\Message\UploadedFile;

class UploadFile
{
    public function save(UploadedFile $file, string $dir, string $filename)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
            chmod($dir, 0777);
        }

        if (!is_writable($dir)) {
            chmod($dir, 0777);
        }

        $filename = $dir . '/' . $filename . $this->getExt($file);
        $file->moveTo($filename);
        chmod($filename, 0777);
    }

    private function getExt($file) {
        return '.' . str_replace('image/', '', $file->getClientMediaType());
    }
}

<?php

declare(strict_types=1);

namespace App\Api\Branch\Repository;

use App\Api\Common\Helper\Facade\UploadFile;
use Exception;

abstract class SaveRepo
{
    protected string $prefix;

    protected function saveCover(array $files, int $branch_id)
    {
        if (isset($files['bgImg'])) {
            $this->saveUploadFile('background', $files['bgImg'], $branch_id);
        } else {
            $this->deleteFile('background', $branch_id);
        }

        if (isset($files['cover'])) {
            $this->saveUploadFile('cover', $files['cover'], $branch_id);
        } else {
            $this->deleteFile('cover', $branch_id);
        }
            
    }

    protected function saveUploadFile($filename, $file, $dir)
    {
        if ($file->getError() !== UPLOAD_ERR_OK) {
            return false;
        }

        $dir = $this->prefix . $dir;

        UploadFile::save($file, $dir, $filename);

        return true;
    }

    protected function deleteFile($pattern, $dir)
    {
        $dir = $this->prefix . $dir;
        $pattern = $dir . '/' . $pattern . '.*';
        array_map('unlink', glob($pattern));

        try {
            rmdir($dir);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    protected function getExt($file) {
        return '.' . str_replace('image/', '', $file->getClientMediaType());
    }
}

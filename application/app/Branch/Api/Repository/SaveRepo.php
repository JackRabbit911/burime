<?php

declare(strict_types=1);

namespace App\Branch\Api\Repository;

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

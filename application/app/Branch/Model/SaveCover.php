<?php declare(strict_types=1);

namespace App\Branch\Model;

use App\Branch\Branch;
use Modules\Image\Im;
use Sys\Helper\Facade\File;
use Nette\Utils\Image;
use Psr\Http\Message\ServerRequestInterface;

class SaveCover
{
    public function save(ServerRequestInterface $request)
    {
        $file = $request->getUploadedFiles()['cover'];

        if ($file->getError() !== UPLOAD_ERR_OK) {
            return null;
        }

        $filename = $this->generateFilename($file);
        $filepath = Branch::COVERPATH . $filename;
        $file->moveTo($filepath);
        chmod($filepath, 0777);
        
        $im = new Im($filepath);
        $im->resize('400x600', Image::Stretch)->save();

        return $filename;
    }

    private function generateFilename($file)
    {
        $mime = $file->getClientMediaType();
        $ext = File::extByImageType($mime);

        if (!is_dir(Branch::COVERPATH)) {
            mkdir(Branch::COVERPATH);
        }

        if (!is_writable(Branch::COVERPATH)) {
            chmod(Branch::COVERPATH, 0777);
        }

        do {
            $filename = uniqid() . $ext;
        } while (is_file($filename));

        return $filename;
    }
}

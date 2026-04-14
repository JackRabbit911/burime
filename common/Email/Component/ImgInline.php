<?php declare(strict_types=1);

namespace Common\Email\Component;

use Sys\Template\Component;
use Exception;

class ImgInline extends Component
{
    private ?string $imageTag = null;
    private string $prefix = STORAGE . 'uploads/';

    public function __construct(string $path, string $alt, string $height = '')
    {
        if (!$this->imageTag) {
            if (!is_file($path)) {
                $path = $this->prefix . $path;
            }
    
            if (!is_file($path)) {
                throw new Exception(sprintf('File %s not found', $path));
            }
           
            $prefix = 'data:image/' . getimagesize($path)['mime'] . ';base64, ';
            $src = $prefix . base64_encode(file_get_contents($path));
    
            if (!empty($height)) {
                $height = ' height="' . $height . '"';
            }
    
            $this->imageTag = '<img src="' . $src .'" alt="' . $alt . '"' . $height .'/>';
        }
    }

    public function render()
    {
        return $this->imageTag;
    }
}

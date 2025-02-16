<?php declare(strict_types = 1);

namespace Auth\Controller;

use Auth\Component\ProfileForm;
use Auth\Middleware\ProfileValidation;
use Auth\Model\AvatarRepo;
use Auth\User;
use Sys\Image\Im;
use Sys\Template\ComponentForm;
use Sys\Helper\Facade\File;
use Az\Route\Route;
use HttpSoft\Response\RedirectResponse;

class Profile extends AuthAbstract
{
    use ComponentForm;

    private string $view = '@auth/profile';

    public function form()
    {
        $this->setReferer();
        return new ProfileForm($this->user);
    }

    #[Route(methods: 'post')]
    #[ProfileValidation]
    public function save(AvatarRepo $repo)
    {
        $this->user->update($this->request->getParsedBody())->save();

        $uploadedFile = $this->request->getUploadedFiles()['avatar'];

        // dd($uploadedFile->getStream()->getContents());

        $repo->save($uploadedFile, $this->user->id);
        // $this->saveAvatar();

        return new RedirectResponse($this->getReferer());
    }

    // private function saveAvatar()
    // {
    //     $file = $this->request->getUploadedFiles()['avatar'];

    //     if ($file->getError() !== UPLOAD_ERR_OK) {
    //         return;
    //     }

    //     $config = config('user');

    //     if (!is_dir($config['avatar_path'])) {
    //         mkdir($config['avatar_path'], 0777);
    //     }

    //     if (!is_writable($config['avatar_path'])) {
    //         chmod($config['avatar_path'], 0777);
    //     }

    //     $avatarPath = $config['avatar_path'] . $this->user->id;

    //     foreach (glob($avatarPath . '.*') as $fn) {
    //         unlink($fn);
    //     }

    //     $mime = $file->getClientMediaType();
    //     $ext = File::extByImageType($mime);

    //     $avatarPath .= $ext;
    //     $file->moveTo($avatarPath);

    //     chmod($avatarPath, 0666);
        
    //     Im::create($avatarPath)
    //         ->thumb($config['avatar_size'])->save();
    // }
}

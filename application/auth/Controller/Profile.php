<?php declare(strict_types = 1);

namespace Auth\Controller;

use Auth\Component\ProfileForm;
use Auth\Middleware\AuthGuardMiddleware;
use Auth\Middleware\ProfileValidation;
use Auth\Model\AvatarRepo;
use Sys\Template\ComponentForm;
use Az\Route\Route;
use HttpSoft\Response\RedirectResponse;

#[AuthGuardMiddleware]
class Profile extends AuthAbstract
{
    use ComponentForm;

    private string $view = '@auth/profile';

    public function form()
    {
        $this->user = $this->model->find($this->user->id);
        $this->setReferer();
        return new ProfileForm($this->user);
    }

    #[Route(methods: 'post')]
    #[ProfileValidation]
    public function save(AvatarRepo $repo)
    {
        $this->user->update($this->request->getParsedBody())->save();
        $uploadedFile = $this->request->getUploadedFiles()['avatar'];
        $repo->save($uploadedFile, $this->user->id);

        return new RedirectResponse($this->getReferer());
    }
}

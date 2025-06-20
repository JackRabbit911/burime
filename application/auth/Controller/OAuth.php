<?php declare(strict_types = 1);

namespace Auth\Controller;

use Auth\Component\AuthForm;
use Auth\Model\TokenAuth;
use Auth\Middleware\AuthGuardMiddleware;
use Auth\Middleware\AuthValidation;
use Auth\Middleware\GuestGuardMiddleware;
use Auth\Model\OAuth as ModelOAuth;
use Az\Route\Route;
use Az\Validation\Middleware\CsrfMiddleware;
use HttpSoft\Response\RedirectResponse;

class OAuth extends AuthAbstract
{
    #[GuestGuardMiddleware]
    public function form(AuthForm $form)
    {
        $ref = $this->setReferer();
        return $form->render();
    }

    #[Route(methods: 'post')]
    #[GuestGuardMiddleware, AuthValidation]
    #[CsrfMiddleware]
    public function login(TokenAuth $tokenAuth, ModelOAuth $oAuth)
    {
        $user = $this->model->getUser();

        $referer = $this->getReferer();
        $this->session->destroy();
        
        $oAuth->login($user);
        $tokenAuth->remember('remember', $user->id);

        return new RedirectResponse($referer);
    }

    #[AuthGuardMiddleware]
    public function logout(TokenAuth $tokenAuth, ModelOAuth $oAuth)
    {
        $referer = $this->getReferer();
        $this->session->destroy();
        
        $tokenAuth->forget($this->request->getCookieParams());
        $oAuth->logout();
        
        return new RedirectResponse($referer);
    }
}

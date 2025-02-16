<?php

namespace Auth\Controller;

use Auth\Component\AuthForm;
use Auth\Model\TokenAuth;
use HttpSoft\Response\RedirectResponse;
use Az\Route\Route;
use Auth\Middleware\AuthGuardMiddleware;
use Auth\Middleware\AuthValidation;
use Auth\Middleware\GuestGuardMiddleware;

class Auth extends AuthAbstract
{
    #[GuestGuardMiddleware]
    public function form(AuthForm $form)
    {
        $ref = $this->setReferer();

        if ($this->user !== null) {
            return new RedirectResponse($ref);
        }

        return $form->render();
    }

    #[Route(methods: 'post')]
    #[GuestGuardMiddleware, AuthValidation]
    public function login(TokenAuth $tokenAuth)
    {
        $user = $this->model->getUser();

        $this->session->remove('uid');
        $this->session->remove('code');
        $this->session->user_id = $user->id;
        $this->session->regenerate(true);

        $tokenAuth->remember('remember', $user->id);

        return new RedirectResponse($this->getReferer());
    }

    #[AuthGuardMiddleware]
    public function logout(TokenAuth $tokenAuth)
    {
        $this->session->destroy();
        $tokenAuth->forget($this->request->getCookieParams());
        $referer = $this->getReferer();
        $referer .= (parse_url($referer, PHP_URL_QUERY) ? '&' : '?') . 'redirect=1';
        
        return new RedirectResponse($referer);
    }
}

<?php

declare(strict_types=1);

namespace Auth\Api\Middleware;

use Attribute;
use Auth\Api\Model\ModelAuth;
use Az\Validation\Validation;
use Az\Validation\Middleware\ValidationMiddleware;
use HttpSoft\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

#[Attribute]
final class AuthValidation extends ValidationMiddleware
{
    public function __construct(
        protected Validation $validation,
        private ModelAuth $model
    )
    {
        parent::__construct($validation);
        $this->model = $model;
    }

    protected function setRules($request)
    {
        $this->validation->rule('email', 'required|email')
            ->rule('password', 'required|password')
            ->rule('password', [$this->model, 'isPairEmailPswd'], ':email')
            ->addMsgPath(APPPATH . 'auth/messages');
    }

    protected function errorHandler(ServerRequestInterface $request): ResponseInterface
    {
        $validationResponse = $this->validation->getResponse();

        if ($validationResponse['password']['status'] === 'error' 
            && $validationResponse['password']['key'] === 'isPairEmailPswd') {
                $validationResponse['email'] = [
                    'status' => 'error',
                    'value' => '',
                    'msg' => '',
                ];
        }

        $response = [
            'success' => false,
            'error' => [
                'email' => [
                    'status' => $validationResponse['email']['status'],
                    'value' => $validationResponse['email']['value'],
                    'message' => $validationResponse['email']['msg'],
                ],
                'password' => [
                    'status' => $validationResponse['password']['status'],
                    'value' => $validationResponse['password']['value'],
                    'message' => $validationResponse['password']['msg'],
                ],
            ],
        ];

        return new JsonResponse($response, 200);
    }
}

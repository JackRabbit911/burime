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
    private ModelAuth $model;

    public function __construct(Validation $validation, ModelAuth $model)
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
            'error' => $validationResponse,
        ];

        return new JsonResponse($response, 200);
    }
}

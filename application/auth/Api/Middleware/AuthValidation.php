<?php

declare(strict_types=1);

namespace Auth\Api\Middleware;

use Attribute;
use Auth\Model\ModelUser;
use Az\Validation\Validation;
use Az\Validation\Middleware\ValidationMiddleware;
use HttpSoft\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Sys\Middleware\CORSMiddleware;

#[Attribute]
final class AuthValidation extends ValidationMiddleware
{
    private ModelUser $model;
    private CORSMiddleware $cors;

    public function __construct(Validation $validation, ModelUser $model, CORSMiddleware $cors)
    {
        parent::__construct($validation);
        $this->model = $model;
        $this->cors = $cors;
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

        $contract = config('api_contracts', $request->getUri()->getPath());
        $headers = $this->cors->getHeaders($request, $contract);
        return new JsonResponse($validationResponse, 200, $headers);
    }
}

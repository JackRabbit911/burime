<?php

declare(strict_types=1);

namespace Adm\Repository;

use Adm\Model\ModelAuth;
use Adm\Model\ModelRefreshToken;
use Adm\Service\Tokens;

class TokensRepo
{
    private $user;

    public function __construct(
        private Tokens $tokens,
        private ModelAuth $modelAuth,
        private ModelRefreshToken $modelRefreshToken,
    )
    {
        $this->user = $modelAuth->getUser();
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getTokens()
    {
        return [
            'X-Refresh' => $this->createRefreshToken(),
            'X-Bearer' => $this->createBearerToken(),
        ];
    }

    public function createBearerToken(): string
    {
        return $this->tokens->encodeJWT($this->user);
    }
    
    public function createRefreshToken(): string
    {
        $data = $this->tokens->generateRefreshToken($this->user->id);
        $this->modelRefreshToken->create($data);

        return $data['token'];
    }
}

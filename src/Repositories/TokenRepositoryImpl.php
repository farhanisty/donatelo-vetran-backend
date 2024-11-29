<?php

namespace Farhanisty\DonateloBackend\Repositories;

use Farhanisty\DonateloBackend\Entity\Token;
use Farhanisty\Vetran\Facades\Connection\Connection;

class TokenRepositoryImpl implements TokenRepository
{
    public function getByToken(string $token): ?Token
    {
        $connection = Connection::getInstance();
        $stmt = $connection->prepare('SELECT * FROM order_tokens WHERE token=?');
        $stmt->execute([$token]);

        $token = $stmt->fetchObject();

        if (!$token) {
            return null;
        }

        return new Token($token->id, $token->token, $token->is_active, $token->created_at, $token->updated_at);
    }

    public function create(string $token): bool
    {
        $connection = Connection::getInstance();
        $stmt = $connection->prepare('INSERT INTO order_tokens(token, created_at, updated_at) VALUE (?, NOW(), NOW())');
        $stmt->execute([$token]);

        return $stmt->rowCount() ? true : false;
    }
}

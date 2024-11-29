<?php

namespace Farhanisty\DonateloBackend\Repositories;

use Farhanisty\DonateloBackend\Entity\Token;

interface TokenRepository
{
    public function create(string $token): bool;

    public function getByToken(string $token): ?Token;
}

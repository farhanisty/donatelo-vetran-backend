<?php

namespace Farhanisty\DonateloBackend\Repositories;

use Farhanisty\DonateloBackend\Repositories\TokenRepository;
use Farhanisty\DonateloBackend\Repositories\TokenRepositoryImpl;

class TokenRepositoryFactory
{
    private static ?TokenRepository $instance = null;

    public static function getInstance(): ?TokenRepository
    {
        if (!self::$instance) {
            self::$instance = new TokenRepositoryImpl();
        }

        return self::$instance;
    }
}

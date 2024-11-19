<?php

namespace Farhanisty\DonateloBackend\Repositories;

use Farhanisty\DonateloBackend\Repositories\MenuRepository;

class MenuRepositoryFactory
{
    private static ?MenuRepository $instance = null;

    public function getInstance(): MenuRepository
    {
        if (!self::$instance) {
            self::$instance = new MenuRepositoryImpl();
        }

        return self::$instance;
    }
}
